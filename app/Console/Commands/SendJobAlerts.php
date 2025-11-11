<?php

namespace App\Console\Commands;

use App\Mail\JobAlertDigestMail;
use App\Models\Job;
use App\Models\JobAlert;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class SendJobAlerts extends Command
{
    protected $signature = 'alerts:send-daily {--since= : Override the starting date (Y-m-d)}';

    protected $description = 'Send daily email digests for job alerts';

    public function handle(): int
    {
        $now = Carbon::now();
        $defaultSince = $now->copy()->subDay()->startOfDay();
        $sinceOption = $this->option('since');
        $since = $sinceOption ? Carbon::parse($sinceOption)->startOfDay() : $defaultSince;

        JobAlert::query()
            ->orderBy('id')
            ->chunk(100, function ($alerts) use ($since, $now) {
                /** @var Collection<int, JobAlert> $alerts */
                foreach ($alerts as $alert) {
                    $windowStart = $alert->last_sent_at?->copy()->startOfDay() ?? $since;
                    $windowEnd = $now->copy();

                    $jobs = $this->matchingJobs($alert, $windowStart, $windowEnd);

                    Mail::to($alert->email)->send(new JobAlertDigestMail(
                        $alert,
                        $jobs,
                        $windowStart,
                        $windowEnd
                    ));

                    $alert->forceFill(['last_sent_at' => $windowEnd])->save();

                    $this->info(sprintf(
                        'Sent alert to %s (%d jobs)',
                        $alert->email,
                        $jobs->count()
                    ));
                }
            });

        return Command::SUCCESS;
    }

    /**
     * @return \Illuminate\Support\Collection<int, Job>
     */
    protected function matchingJobs(JobAlert $alert, Carbon $start, Carbon $end): Collection
    {
        return Job::query()
            ->published()
            ->whereBetween('published_date', [$start, $end])
            ->when($alert->keyword, function ($query) use ($alert) {
                $keyword = '%'.$alert->keyword.'%';
                $query->where(function ($sub) use ($keyword) {
                    $sub->where('position', 'like', $keyword)
                        ->orWhere('description', 'like', $keyword);
                });
            })
            ->when($alert->company, fn ($query) => $query->where('company', 'like', '%'.$alert->company.'%'))
            ->when($alert->location, fn ($query) => $query->where('location', 'like', '%'.$alert->location.'%'))
            ->orderByDesc('published_date')
            ->get();
    }
}
