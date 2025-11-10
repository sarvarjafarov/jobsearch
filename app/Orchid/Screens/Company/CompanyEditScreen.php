<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Company;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class CompanyEditScreen extends Screen
{
    public Company $company;

    public $permission = ['platform.companies'];

    public function query(Company $company): iterable
    {
        return [
            'company' => $company,
            'perks_text' => collect($company->perks ?? [])->implode("\n"),
        ];
    }

    public function name(): ?string
    {
        return $this->company->exists ? 'Edit company' : 'Create company';
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Delete')
                ->icon('bs.trash3')
                ->method('remove')
                ->confirm('Are you sure you want to delete this company?')
                ->canSee($this->company->exists),
            Button::make('Save')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('company.name')
                    ->title('Name')
                    ->required(),
                Input::make('company.slug')
                    ->title('Slug')
                    ->help('Used in URLs. Leave blank to auto-generate.')
                    ->placeholder('auto-generate'),
                Input::make('company.industry')
                    ->title('Industry'),
                Input::make('company.headquarters')
                    ->title('Headquarters'),
                Input::make('company.size')
                    ->title('Team size'),
                Input::make('company.website_url')
                    ->title('Website')
                    ->type('url'),
                Input::make('company.logo_url')
                    ->title('Logo URL')
                    ->placeholder('https://...'),
                Input::make('company.founded_year')
                    ->title('Founded year')
                    ->type('number'),
                Input::make('company.rating')
                    ->title('Rating')
                    ->type('number')
                    ->step('0.1')
                    ->help('Glassdoor-style rating out of 5.0'),
                TextArea::make('company.description')
                    ->rows(4)
                    ->title('About')
                    ->required(),
                TextArea::make('perks_text')
                    ->rows(3)
                    ->title('Perks (one per line)')
                    ->placeholder("Remote friendly\nLearning budget\nEquity"),
            ])->title('Company details'),
        ];
    }

    public function save(Company $company, Request $request)
    {
        $validated = $request->validate([
            'company.name' => ['required', 'string', 'max:255'],
            'company.slug' => ['nullable', 'string', 'max:255', Rule::unique(Company::class, 'slug')->ignore($company)],
            'company.industry' => ['nullable', 'string', 'max:255'],
            'company.headquarters' => ['nullable', 'string', 'max:255'],
            'company.size' => ['nullable', 'string', 'max:255'],
            'company.website_url' => ['nullable', 'url', 'max:255'],
            'company.logo_url' => ['nullable', 'url', 'max:255'],
            'company.founded_year' => ['nullable', 'integer'],
            'company.rating' => ['nullable', 'numeric', 'between:0,5'],
            'company.description' => ['nullable', 'string'],
            'perks_text' => ['nullable', 'string'],
        ]);

        $perks = collect(preg_split('/\r\n|\r|\n/', $validated['perks_text'] ?? ''))
            ->filter()
            ->values()
            ->all();

        $company->fill(array_merge($validated['company'], [
            'perks' => $perks,
        ]))->save();

        Toast::info('Company saved.');

        return redirect()->route('platform.companies.edit', $company);
    }

    public function remove(Company $company)
    {
        $company->jobs()->update(['company_id' => null]);
        $company->delete();

        Toast::info('Company removed.');

        return redirect()->route('platform.companies.list');
    }
}
