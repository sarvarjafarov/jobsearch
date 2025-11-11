<?php

namespace App\Services\Scrapers;

use App\Models\Company;
use App\Models\Job;
use App\Support\JobDescriptionFormatter;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class JobSearchAzScraper
{
    public function __construct(
        protected array $config = []
    ) {
        $this->config = $config ?: config('jobsearch.jobsearch_az');
    }

    public function fetch(): Collection
    {
        $results = collect();
        $limit = $this->config['limit'] ?? 50;
        $page = 1;

        while ($results->count() < $limit) {
            $url = $this->config['base_url'].sprintf($this->config['listing_path'], $page);
            $response = $this->http()->get($url);

            if ($response->failed()) {
                logger()->warning('Jobsearch.az request failed', ['url' => $url, 'status' => $response->status()]);
                break;
            }

            $crawler = new Crawler($response->body());
            $cards = $crawler->filter($this->config['selectors']['job_card']);

            if ($cards->count() === 0) {
                break;
            }

            $cards->each(function (Crawler $node) use (&$results, $limit) {
                if ($results->count() >= $limit) {
                    return;
                }

                $titleNode = $node->filter($this->config['selectors']['title']);
                if ($titleNode->count() === 0) {
                    return;
                }

                $title = trim($titleNode->text());
                $link = $this->absoluteUrl($titleNode->attr('href'));
                $companyName = trim(optional($node->filter($this->config['selectors']['company'])->first())->text('')) ?: 'Unknown company';

                [$published, $deadline] = $this->extractDatesFromCard($node);
                $detail = $this->fetchDetailData($link);

                $results->push([
                    'position' => $title,
                    'company' => $detail['company'] ?? $companyName,
                    'location' => $detail['location'],
                    'published_date' => $detail['published'] ?? $published,
                    'deadline_date' => $detail['deadline'] ?? $deadline,
                    'description' => $detail['description'],
                    'apply_url' => $link,
                ]);
            });

            $page++;
        }

        return $results->take($limit);
    }

    public function import(Collection $jobs): int
    {
        $count = 0;

        foreach ($jobs as $jobData) {
            $company = Company::firstOrCreate(['name' => $jobData['company']], [
                'headquarters' => $jobData['location'],
            ]);

            $job = Job::updateOrCreate(
                ['source_url' => $jobData['apply_url']],
                [
                    'position' => $jobData['position'],
                    'company' => $company->name,
                    'company_id' => $company->id,
                    'description' => $jobData['description'],
                    'published_date' => $jobData['published_date'] ?? now(),
                    'deadline_date' => $jobData['deadline_date'] ?? now()->addMonth(),
                    'location' => $jobData['location'] ?? 'Baku',
                    'apply_url' => $jobData['apply_url'],
                    'status' => Job::STATUS_PUBLISHED,
                    'source' => 'jobsearch.az',
                ]
            );

            $count += (int) $job->wasRecentlyCreated || $job->wasChanged();
        }

        return $count;
    }

    protected function fetchDetailData(?string $url): array
    {
        if (! $url) {
            return [
                'description' => '',
                'published' => null,
                'deadline' => null,
                'company' => null,
                'location' => null,
            ];
        }

        try {
            $html = $this->http()->get($url)->body();
            $crawler = new Crawler($html);

            $descriptionSelector = $this->config['selectors']['detail_description'] ?? '.vacancy__description';
            $descriptionNode = $crawler->filter($descriptionSelector);
            $description = '';

            if ($descriptionNode->count()) {
                $description = JobDescriptionFormatter::sanitizeHtml($descriptionNode->html());
            }

            $deadlineSelector = $this->config['selectors']['detail_deadline'] ?? '.vacancy__dead-line';
            $deadlineNode = $deadlineSelector ? $crawler->filter($deadlineSelector) : null;
            $deadlineText = $deadlineNode && $deadlineNode->count() ? $deadlineNode->text() : null;

            $companySelector = $this->config['selectors']['detail_company'] ?? '.company__title h2';
            $companyNode = $crawler->filter($companySelector);

            $publishedSelector = $this->config['selectors']['detail_published'] ?? null;
            $publishedNode = $publishedSelector ? $crawler->filter($publishedSelector) : null;
            $publishedText = $publishedNode && $publishedNode->count() ? $publishedNode->text() : null;

            $locationSelector = $this->config['selectors']['detail_location'] ?? null;
            $locationNode = $locationSelector ? $crawler->filter($locationSelector) : null;

            return [
                'description' => $description ?: JobDescriptionFormatter::toHtml($descriptionNode->count() ? $descriptionNode->text() : ''),
                'published' => $this->extractDateFromSentence($publishedText),
                'deadline' => $this->extractDateFromSentence($deadlineText),
                'company' => $companyNode->count() ? trim($companyNode->text()) : null,
                'location' => $locationNode && $locationNode->count() ? trim($locationNode->text()) : null,
            ];
        } catch (\Throwable $e) {
            logger()->warning('jobsearch.az detail fetch failed', ['url' => $url, 'message' => $e->getMessage()]);

            return [
                'description' => '',
                'published' => null,
                'deadline' => null,
                'company' => null,
                'location' => null,
            ];
        }
    }

    protected function extractDatesFromCard(Crawler $node): array
    {
        $dateNodes = $node->filter('li.d-none.d-lg-block');
        $published = $this->parseDate($dateNodes->count() ? $dateNodes->eq(0)->text('') : null);
        $deadline = $this->parseDate($dateNodes->count() > 1 ? $dateNodes->eq(1)->text('') : null);

        if (! $published) {
            $mobileDateNode = $node->filter('.vacancies__mobile-area span');
            if ($mobileDateNode->count()) {
                $published = $this->parseDate($mobileDateNode->first()->text());
            }
        }

        return [$published, $deadline];
    }

    protected function extractDateFromSentence(?string $text): ?Carbon
    {
        if (! $text) {
            return null;
        }

        if (preg_match('/(\d{1,2}\s+[^\d]+)/u', $text, $match)) {
            return $this->parseDate($match[0]);
        }

        if (preg_match('/\d{1,2}\.\d{1,2}\.\d{4}/', $text, $match)) {
            return Carbon::createFromFormat('d.m.Y', $match[0]);
        }

        if (preg_match('/\d{4}-\d{2}-\d{2}/', $text, $match)) {
            return Carbon::parse($match[0]);
        }

        return $this->parseDate($text);
    }

    protected function parseDate(?string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        $value = Str::of($value)->lower()->trim();

        if ($value->is('bu gün') || $value->is('bugün') || $value->is('bu gun')) {
            return Carbon::today();
        }

        if ($value->contains('dün') || $value->is('dunen')) {
            return Carbon::today()->subDay();
        }

        $ascii = Str::ascii($value);

        if (preg_match('/(\d{1,2})\.(\d{1,2})\.(\d{2,4})/', $ascii, $dotMatches)) {
            $year = strlen($dotMatches[3]) === 2 ? (int) ('20'.$dotMatches[3]) : (int) $dotMatches[3];
            return Carbon::createFromDate($year, (int) $dotMatches[2], (int) $dotMatches[1]);
        }

        if (preg_match('/(\d{4})-(\d{2})-(\d{2})/', $ascii, $isoMatches)) {
            return Carbon::createFromDate((int) $isoMatches[1], (int) $isoMatches[2], (int) $isoMatches[3]);
        }

        if (! preg_match('/(\d{1,2})\s+([a-z]+)/i', $ascii, $matches)) {
            return null;
        }

        $day = (int) $matches[1];
        $monthLabel = $matches[2];

        $months = [
            'yan' => 1, 'yanvar' => 1,
            'fev' => 2, 'fevral' => 2,
            'mart' => 3,
            'apr' => 4, 'aprel' => 4,
            'may' => 5,
            'iyun' => 6,
            'iyul' => 7,
            'avg' => 8, 'avqust' => 8,
            'sen' => 9, 'sentyabr' => 9,
            'okt' => 10, 'oktyabr' => 10,
            'noy' => 11, 'noyabr' => 11,
            'dek' => 12, 'dekabr' => 12,
            'jan' => 1, 'january' => 1,
            'feb' => 2, 'february' => 2,
            'mar' => 3, 'march' => 3,
            'apr' => 4, 'april' => 4,
            'jun' => 6, 'june' => 6,
            'jul' => 7, 'july' => 7,
            'aug' => 8, 'august' => 8,
            'sep' => 9, 'sept' => 9, 'september' => 9,
            'oct' => 10, 'october' => 10,
            'nov' => 11, 'november' => 11,
            'dec' => 12, 'december' => 12,
        ];

        $month = collect($months)->first(function ($value, $key) use ($monthLabel) {
            return Str::startsWith($monthLabel, $key);
        });

        if (! $month) {
            return null;
        }

        $year = Carbon::today()->year;
        if (preg_match('/\d{4}/', $ascii, $yearMatch)) {
            $year = (int) $yearMatch[0];
        }

        return Carbon::createFromDate($year, $month, $day);
    }

    protected function absoluteUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, 'http')) {
            return $path;
        }

        return rtrim($this->config['base_url'], '/').'/'.ltrim($path, '/');
    }

    protected function http()
    {
        return Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 14_0) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15',
                'Accept' => 'text/html,application/xhtml+xml',
            ])->retry(3, 200);
    }
}
