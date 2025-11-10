<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Job;

use App\Models\Company;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class JobEditScreen extends Screen
{
    /**
     * Current job model.
     */
    public Job $job;

    /**
     * Permissions required for the screen.
     *
     * @var array<int, string>
     */
    public array $permission = ['platform.jobs'];

    /**
     * Fetch data to display.
     */
    public function query(Job $job): iterable
    {
        return [
            'job' => $job,
        ];
    }

    /**
     * Screen title.
     */
    public function name(): ?string
    {
        return $this->job->exists ? 'Edit Job' : 'Create Job';
    }

    /**
     * Screen action buttons.
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Delete')
                ->icon('bs.trash3')
                ->confirm('Delete this job? It will disappear from the public site.')
                ->method('remove')
                ->canSee($this->job->exists),

            Button::make('Save')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    /**
     * Screen layouts.
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('job.position')
                    ->title('Position')
                    ->placeholder('Product Designer')
                    ->required()
                    ->maxlength(255),

                Input::make('job.company')
                    ->title('Company')
                    ->placeholder('Acme Inc.')
                    ->required()
                    ->maxlength(255),

                Relation::make('job.company_id')
                    ->title('Link to company profile')
                    ->fromModel(Company::class, 'name')
                    ->help('Optional: tie this job to a richer company profile.'),

                Input::make('job.location')
                    ->title('Location')
                    ->placeholder('Remote, San Francisco, etc.')
                    ->maxlength(255),

                Input::make('job.apply_url')
                    ->title('Application URL')
                    ->type('url')
                    ->placeholder('https://company.com/careers/apply')
                    ->maxlength(2048),

                Select::make('job.status')
                    ->title('Status')
                    ->options(Job::STATUSES)
                    ->required(),

                DateTimer::make('job.published_date')
                    ->title('Published Date')
                    ->format('Y-m-d')
                    ->allowInput()
                    ->required(),

                DateTimer::make('job.deadline_date')
                    ->title('Deadline Date')
                    ->format('Y-m-d')
                    ->allowInput()
                    ->required(),

                TextArea::make('job.description')
                    ->title('Description')
                    ->rows(6)
                    ->required()
                    ->placeholder('Describe the opportunity, responsibilities, and ideal profile.'),
            ])->title('Job Details')
                ->description('These values sync directly with the public job board.'),
        ];
    }

    /**
     * Persist changes.
     */
    public function save(Job $job, Request $request)
    {
        $validated = $request->validate([
            'job.position' => ['required', 'string', 'max:255'],
            'job.company' => ['required', 'string', 'max:255'],
            'job.company_id' => ['nullable', 'exists:companies,id'],
            'job.location' => ['nullable', 'string', 'max:255'],
            'job.description' => ['required', 'string'],
            'job.published_date' => ['required', 'date'],
            'job.deadline_date' => ['required', 'date', 'after_or_equal:job.published_date'],
            'job.apply_url' => ['nullable', 'url', 'max:2048'],
            'job.status' => ['required', 'string', Rule::in(array_keys(Job::STATUSES))],
        ]);

        $job->fill($validated['job'])->save();

        if ($job->company_id && $company = Company::find($job->company_id)) {
            $job->update(['company' => $job->company ?: $company->name]);
        }

        Toast::info('Job saved successfully.');

        return redirect()->route('platform.jobs.edit', $job);
    }

    /**
     * Delete the current job.
     */
    public function remove(Job $job)
    {
        $job->delete();

        Toast::info('Job deleted.');

        return redirect()->route('platform.jobs.list');
    }
}
