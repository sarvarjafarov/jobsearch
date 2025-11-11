<?php

namespace App\Orchid\Screens;

use App\Models\SeoMeta;
use App\Services\SeoManager;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SeoMetaEditScreen extends Screen
{
    public ?SeoMeta $meta = null;

    public array $permission = ['platform.seo'];

    public function query(SeoMeta $meta): iterable
    {
        return [
            'meta' => $meta,
        ];
    }

    public function name(): ?string
    {
        return $this->meta && $this->meta->exists ? 'Edit SEO Entry' : 'Create SEO Entry';
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Delete')
                ->icon('bs.trash3')
                ->confirm('Delete this SEO entry?')
                ->method('remove')
                ->canSee($this->meta && $this->meta->exists),
            Button::make('Save')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('meta.route_name')
                    ->title('Route name')
                    ->help('Laravel route name (ex: home, blog.show).'),
                Input::make('meta.path')
                    ->title('URL path')
                    ->placeholder('blog/my-post')
                    ->help('Optional. Without domain or leading slash.'),
                Input::make('meta.meta_title')
                    ->title('Meta title'),
                TextArea::make('meta.meta_description')
                    ->title('Meta description')
                    ->rows(3),
                Input::make('meta.meta_keywords')
                    ->title('Keywords (comma separated)'),
                Input::make('meta.canonical_url')
                    ->title('Canonical URL')
                    ->placeholder('https://example.com/page'),
                Input::make('meta.og_title')
                    ->title('OG title'),
                TextArea::make('meta.og_description')
                    ->title('OG description')
                    ->rows(3),
                Input::make('meta.og_image')
                    ->title('OG image URL'),
                Input::make('meta.twitter_image')
                    ->title('Twitter image URL'),
                Code::make('meta.schema_json')
                    ->title('Schema (JSON-LD)')
                    ->language('json')
                    ->value($this->meta && $this->meta->schema_json
                        ? json_encode($this->meta->schema_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                        : null)
                    ->help('Paste valid JSON'),
                Switcher::make('meta.noindex')
                    ->title('Noindex')
                    ->sendTrueOrFalse(),
            ]),
        ];
    }

    public function save(SeoMeta $meta, Request $request)
    {
        $data = $request->validate([
            'meta.route_name' => ['nullable', 'string', 'max:255'],
            'meta.path' => ['nullable', 'string', 'max:255'],
            'meta.meta_title' => ['nullable', 'string', 'max:255'],
            'meta.meta_description' => ['nullable', 'string'],
            'meta.meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta.canonical_url' => ['nullable', 'url'],
            'meta.og_title' => ['nullable', 'string', 'max:255'],
            'meta.og_description' => ['nullable', 'string'],
            'meta.og_image' => ['nullable', 'url'],
            'meta.twitter_image' => ['nullable', 'url'],
            'meta.schema_json' => ['nullable', 'json'],
            'meta.noindex' => ['boolean'],
        ])['meta'];

        $data = collect($data)->map(function ($value) {
            return is_string($value) ? trim($value) : $value;
        })->all();

        if (array_key_exists('route_name', $data)) {
            $data['route_name'] = $data['route_name'] ?: null;
        }

        if (array_key_exists('path', $data)) {
            $path = trim($data['path'], '/');
            $data['path'] = $path !== '' ? $path : null;
        }

        if (array_key_exists('schema_json', $data)) {
            $data['schema_json'] = filled($data['schema_json'])
                ? json_decode($data['schema_json'], true)
                : null;
        }

        $meta->fill($data)->save();
        SeoManager::flushCache();

        Toast::info('SEO entry saved.');

        return redirect()->route('platform.seo.meta.edit', $meta);
    }

    public function remove(SeoMeta $meta)
    {
        $meta->delete();
        SeoManager::flushCache();
        Toast::info('SEO entry deleted.');

        return redirect()->route('platform.seo.meta.list');
    }
}
