<?php

namespace App\Jobs;

use App\Services\GoogleIndexingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubmitUrlToGoogleIndexing implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public string $url,
        public string $type = 'URL_UPDATED'
    ) {
        $this->onQueue('default');
    }

    public function handle(GoogleIndexingService $service): void
    {
        $service->publish($this->url, $this->type);
    }
}
