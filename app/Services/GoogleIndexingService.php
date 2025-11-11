<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Log;

class GoogleIndexingService
{
    protected ?GoogleClient $client = null;

    public function publish(string $url, string $type = 'URL_UPDATED'): void
    {
        if (! config('google-indexing.enabled')) {
            Log::debug('Google Indexing disabled; skipped', ['url' => $url, 'type' => $type]);

            return;
        }

        $credentialsPath = config('google-indexing.credentials');

        if (! $credentialsPath || ! file_exists($credentialsPath)) {
            Log::warning('Google Indexing credentials missing or unreadable', ['path' => $credentialsPath]);

            return;
        }

        try {
            $httpClient = $this->client($credentialsPath)->authorize();

            $response = $httpClient->post('https://indexing.googleapis.com/v3/urlNotifications:publish', [
                'json' => [
                    'url' => $url,
                    'type' => $type,
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 10,
            ]);

            Log::info('Google Indexing API notification sent', [
                'url' => $url,
                'type' => $type,
                'status' => $response->getStatusCode(),
            ]);
        } catch (\Throwable $exception) {
            Log::error('Google Indexing API call failed', [
                'message' => $exception->getMessage(),
                'url' => $url,
                'type' => $type,
            ]);
        }
    }

    protected function client(string $credentialsPath): GoogleClient
    {
        if ($this->client) {
            return $this->client;
        }

        $client = new GoogleClient();
        $client->setAuthConfig($credentialsPath);
        $client->setScopes(config('google-indexing.scopes', []));

        return $this->client = $client;
    }
}
