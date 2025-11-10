<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Job;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'stats' => [
                'pending' => Job::where('status', Job::STATUS_PENDING)->count(),
                'published' => Job::published()->count(),
                'upcoming_deadlines' => Job::published()
                    ->whereBetween('deadline_date', [now(), now()->addWeeks(2)])
                    ->count(),
            ],
            'pendingJobs' => Job::where('status', Job::STATUS_PENDING)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get(['id', 'position', 'company', 'created_at']),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Jobify Dashboard';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Monitor submissions, approvals, and deadlines from a single view.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('orchid.dashboard'),
        ];
    }
}
