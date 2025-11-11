<?php

namespace App\Orchid\Screens;

use App\Models\SeoMeta;
use App\Services\SeoManager;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class SeoMetaListScreen extends Screen
{
    public array $permission = ['platform.seo'];

    public function query(): iterable
    {
        return [
            'entries' => SeoMeta::orderByDesc('updated_at')->paginate(15),
        ];
    }

    public function name(): ?string
    {
        return 'SEO Entries';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Add entry')
                ->icon('bs.plus-circle')
                ->route('platform.seo.meta.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('entries', [
                TD::make('route_name', 'Route'),
                TD::make('path', 'Path')
                    ->render(fn (SeoMeta $meta) => $meta->path ?: '—'),
                TD::make('meta_title', 'Title')
                    ->render(fn (SeoMeta $meta) => e(Str::limit($meta->meta_title ?? '—', 60))),
                TD::make('updated_at', 'Updated')
                    ->sort()
                    ->render(fn (SeoMeta $meta) => $meta->updated_at->diffForHumans()),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn (SeoMeta $meta) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Edit')
                                ->route('platform.seo.meta.edit', $meta)
                                ->icon('bs.pencil'),
                            Button::make('Delete')
                                ->confirm('Delete this SEO entry?')
                                ->icon('bs.trash3')
                                ->method('remove', [
                                    'meta' => $meta->id,
                                ]),
                        ])),
            ]),
        ];
    }

    public function remove(SeoMeta $meta)
    {
        $meta->delete();
        SeoManager::flushCache();
        Toast::info('SEO entry removed.');
    }
}
