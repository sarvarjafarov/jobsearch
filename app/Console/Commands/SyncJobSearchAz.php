<?php

namespace App\Console\Commands;

use App\Services\Scrapers\JobSearchAzScraper;
use Illuminate\Console\Command;

class SyncJobSearchAz extends Command
{
    protected $signature = 'jobsearch:sync';

    protected $description = 'Scrape jobsearch.az and import fresh roles';

    public function handle(JobSearchAzScraper $scraper)
    {
        set_time_limit(180);
        $this->info("Fetching latest vacancies from jobsearch.az …");

        $collection = $scraper->fetch();
        $this->info("Parsed {$collection->count()} listings. Saving…");

        $inserted = $scraper->import($collection);

        $this->info("Updated {$inserted} jobs from jobsearch.az.");

        return Command::SUCCESS;
    }
}
