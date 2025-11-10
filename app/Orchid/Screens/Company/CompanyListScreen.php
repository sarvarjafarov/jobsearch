<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Company;

use App\Models\Company;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CompanyListScreen extends Screen
{
    public $permission = ['platform.companies'];

    public $name = 'Companies';

    public $description = 'Keep company profiles current and connect them to active roles.';

    public function query(): iterable
    {
        return [
            'companies' => Company::withCount('publishedJobs')
                ->orderBy('name')
                ->paginate(15),
        ];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Add company')
                ->icon('bs.plus-circle')
                ->route('platform.companies.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('companies', [
                TD::make('name', 'Name')
                    ->sort()
                    ->render(fn (Company $company) => Link::make($company->name)->route('platform.companies.edit', $company)),
                TD::make('industry', 'Industry')
                    ->sort()
                    ->render(fn (Company $company) => e($company->industry ?? '—')),
                TD::make('headquarters', 'HQ')
                    ->defaultHidden()
                    ->render(fn (Company $company) => e($company->headquarters ?? '—')),
                TD::make('rating', 'Rating')
                    ->render(fn (Company $company) => $company->rating ? number_format($company->rating, 1) : '—'),
                TD::make('published_jobs_count', 'Open roles')
                    ->sort()
                    ->render(fn (Company $company) => $company->published_jobs_count),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn (Company $company) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('View public page')
                                ->icon('bs.box-arrow-up-right')
                                ->route('companies.show', $company)
                                ->target('_blank'),
                            Link::make('Edit')
                                ->icon('bs.pencil')
                                ->route('platform.companies.edit', $company),
                            Button::make('Delete')
                                ->icon('bs.trash3')
                                ->confirm('Removing this profile will detach it from jobs. Continue?')
                                ->method('remove', ['company' => $company->id]),
                        ])),
            ]),
        ];
    }

    public function remove(Company $company)
    {
        $company->jobs()->update(['company_id' => null]);
        $company->delete();
        Toast::info('Company removed.');
    }
}
