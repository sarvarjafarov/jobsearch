<?php

namespace App\Orchid\Screens;

use App\Models\Job;
use App\Services\Scrapers\JobSearchAzScraper;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class JobSearchSyncScreen extends Screen
{
    public array $permission = ['platform.scraper'];

    public function query(): iterable
    {
        $query = Job::query()->where('source', 'jobsearch.az');

        return [
            'stats' => [
                'total' => $query->count(),
                'last_sync' => $query->max('updated_at'),
            ],
            'recentJobs' => $query->orderByDesc('updated_at')->limit(5)->get(),
        ];
    }

    public function name(): ?string
    {
        return 'Jobsearch.az Importer';
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Sync latest 50 vacancies')
                ->icon('bs.cloud-download')
                ->method('sync')
                ->class('btn btn-primary'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::view('orchid.jobsearch-sync'),
        ];
    }

    public function sync(JobSearchAzScraper $scraper)
    {
        set_time_limit(180);
        $collection = $scraper->fetch();
        $imported = $scraper->import($collection);

        Toast::info("Jobify pulled {$collection->count()} listings and updated {$imported} records.");
    }
}
