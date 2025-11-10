<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Job;

use App\Models\Job;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class JobListScreen extends Screen
{
    /**
     * The screen name.
     *
     * @var string
     */
    public $name = 'Jobs';

    /**
     * Description shown under the name.
     *
     * @var string
     */
    public $description = 'Manage the openings powering the marketing site.';

    /**
     * Permissions required to view this screen.
     *
     * @var array<int, string>
     */
    public array $permission = ['platform.jobs'];

    /**
     * Fetch data for display.
     */
    public function query(): iterable
    {
        return [
            'jobs' => Job::orderByDesc('published_date')->paginate(15),
        ];
    }

    /**
     * Screen action buttons.
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Post new role')
                ->icon('bs.plus-circle')
                ->route('platform.jobs.create'),
        ];
    }

    /**
     * Screen layout definition.
     */
    public function layout(): iterable
    {
        return [
            Layout::table('jobs', [
                TD::make('position', 'Position')
                    ->render(fn (Job $job) => Link::make($job->position)->route('platform.jobs.edit', $job))
                    ->sort(),
                TD::make('company', 'Company')
                    ->sort()
                    ->render(fn (Job $job) => e($job->company)),
                TD::make('location', 'Location')
                    ->defaultHidden()
                    ->render(fn (Job $job) => $job->location ?? '—'),
                TD::make('status', 'Status')
                    ->render(function (Job $job) {
                        $isPublished = $job->status === Job::STATUS_PUBLISHED;
                        $classes = $isPublished
                            ? 'bg-emerald-100 text-emerald-800'
                            : 'bg-amber-100 text-amber-800';

                        $label = Job::STATUSES[$job->status] ?? ucfirst($job->status ?? 'pending');

                        return sprintf(
                            '<span class="inline-flex items-center rounded-xl px-3 py-1 text-xs font-semibold %s">%s</span>',
                            $classes,
                            e($label)
                        );
                    }),
                TD::make('published_date', 'Published')
                    ->render(fn (Job $job) => optional($job->published_date)->format('M d, Y'))
                    ->sort(),
                TD::make('deadline_date', 'Deadline')
                    ->render(fn (Job $job) => optional($job->deadline_date)->format('M d, Y'))
                    ->sort(),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_RIGHT)
                    ->render(fn (Job $job) => DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Edit')
                                ->icon('bs.pencil')
                                ->route('platform.jobs.edit', $job),
                            $job->status === Job::STATUS_PUBLISHED
                                ? Button::make('Mark as pending')
                                    ->icon('bs.pause-circle')
                                    ->method('hold', ['job' => $job->id])
                                : Button::make('Publish now')
                                    ->icon('bs.check-circle')
                                    ->method('publish', ['job' => $job->id]),
                            Button::make('Delete')
                                ->icon('bs.trash3')
                                ->confirm('This will remove the job from both the admin panel and the public site. Continue?')
                                ->method('remove', [
                                    'job' => $job->id,
                                ]),
                        ])
                    ),
            ]),
        ];
    }

    /**
     * Remove a job posting.
     */
    public function remove(Job $job)
    {
        $job->delete();

        Toast::info('Job removed successfully.');
    }

    public function publish(Job $job)
    {
        if (blank($job->apply_url)) {
            Toast::warning('Add an application URL before publishing.');

            return;
        }

        $job->update(['status' => Job::STATUS_PUBLISHED]);

        Toast::success('Job is now live on the public site.');
    }

    public function hold(Job $job)
    {
        $job->update(['status' => Job::STATUS_PENDING]);

        Toast::info('Job marked as pending review.');
    }
}
