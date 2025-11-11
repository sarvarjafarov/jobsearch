<?php

namespace App\Services;

use App\Models\SeoMeta;
use App\Models\SeoSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SeoManager
{
    private const SETTINGS_CACHE_KEY = 'seo:settings';
    private const ENTRIES_CACHE_KEY = 'seo:meta.entries';

    public function resolve(Request $request): array
    {
        $routeName = optional($request->route())->getName();
        $path = trim($request->path(), '/');

        $settings = $this->settings();
        $entry = $this->matchEntry($routeName, $path);

        $defaultTitle = $settings->default_title ?? config('app.name');
        $defaultDescription = $settings->default_description;
        $defaultOgImage = $settings->default_og_image;

        $ogTitle = $entry?->og_title
            ?? $entry?->meta_title
            ?? $defaultTitle;

        $ogDescription = $entry?->og_description
            ?? $entry?->meta_description
            ?? $defaultDescription;

        $ogImage = $entry?->og_image ?? $defaultOgImage;
        $twitterImage = $entry?->twitter_image ?? $defaultOgImage ?? $ogImage;

        return [
            'title' => $entry?->meta_title ?? $defaultTitle,
            'description' => $entry?->meta_description ?? $defaultDescription,
            'keywords' => $entry?->meta_keywords ?? $settings->default_keywords,
            'canonical' => $entry?->canonical_url ?? url()->current(),
            'og_title' => $ogTitle,
            'og_description' => $ogDescription,
            'og_image' => $ogImage,
            'twitter_image' => $twitterImage,
            'schema' => $entry?->schema_json ?? $settings->global_schema,
            'noindex' => (bool) ($entry?->noindex ?? false),
            'favicon' => $settings->favicon_path ?? asset('favicon.ico'),
            'site_name' => $settings->site_name ?? config('app.name'),
        ];
    }

    public static function flushCache(): void
    {
        Cache::forget(self::SETTINGS_CACHE_KEY);
        Cache::forget(self::ENTRIES_CACHE_KEY);
    }

    protected function settings(): SeoSetting
    {
        return Cache::remember(self::SETTINGS_CACHE_KEY, now()->addMinutes(30), fn () => SeoSetting::firstOrNew());
    }

    protected function matchEntry(?string $routeName, ?string $path): ?SeoMeta
    {
        $entries = $this->entries();

        if ($routeName) {
            $matchByRoute = $entries->firstWhere('route_name', $routeName);
            if ($matchByRoute) {
                return $matchByRoute;
            }
        }

        if ($path) {
            $normalizedPath = trim($path, '/');
            return $entries->first(fn (SeoMeta $meta) => $meta->path === $normalizedPath);
        }

        return null;
    }

    /**
     * @return Collection<int, SeoMeta>
     */
    protected function entries(): Collection
    {
        return Cache::remember(self::ENTRIES_CACHE_KEY, now()->addMinutes(30), fn () => SeoMeta::all());
    }
}
