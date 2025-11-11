<?php

namespace App\Orchid\Screens;

use App\Models\SeoSetting;
use App\Services\SeoManager;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Code;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SeoSettingsScreen extends Screen
{
    public ?SeoSetting $settings = null;

    public array $permission = ['platform.seo'];

    public function query(): iterable
    {
        $this->settings = SeoSetting::firstOrCreate([]);

        return [
            'settings' => $this->settings,
        ];
    }

    public function name(): ?string
    {
        return 'SEO Settings';
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Save settings')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('settings.site_name')->title('Site name'),
                Input::make('settings.default_title')->title('Default meta title'),
                TextArea::make('settings.default_description')->title('Default description')->rows(3),
                Input::make('settings.default_keywords')->title('Default keywords'),
                Input::make('settings.default_og_image')->title('Default OG image URL'),
                Input::make('settings.default_twitter_image')->title('Default Twitter image URL'),
                Input::make('settings.favicon_path')->title('Favicon URL'),
                Code::make('settings.global_schema')
                    ->title('Global schema (JSON-LD)')
                    ->language('json')
                    ->value($this->settings && $this->settings->global_schema
                        ? json_encode($this->settings->global_schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                        : null),
            ]),
        ];
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'settings.site_name' => ['nullable', 'string', 'max:255'],
            'settings.default_title' => ['nullable', 'string', 'max:255'],
            'settings.default_description' => ['nullable', 'string'],
            'settings.default_keywords' => ['nullable', 'string'],
            'settings.default_og_image' => ['nullable', 'url'],
            'settings.default_twitter_image' => ['nullable', 'url'],
            'settings.favicon_path' => ['nullable', 'url'],
            'settings.global_schema' => ['nullable', 'json'],
        ])['settings'];

        if (array_key_exists('global_schema', $data)) {
            $data['global_schema'] = filled($data['global_schema'])
                ? json_decode($data['global_schema'], true)
                : null;
        }

        $settings = SeoSetting::firstOrCreate([]);
        $settings->fill($data)->save();

        Toast::info('SEO settings saved.');
        SeoManager::flushCache();
    }
}
