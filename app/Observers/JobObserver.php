<?php

namespace App\Observers;

use App\Jobs\SubmitUrlToGoogleIndexing;
use App\Models\Job;

class JobObserver
{
    public function created(Job $job): void
    {
        if ($job->status === Job::STATUS_PUBLISHED) {
            $this->notifyUpdated($job);
        }
    }

    public function updated(Job $job): void
    {
        if ($job->status === Job::STATUS_PUBLISHED && ($job->wasChanged('status') || $job->wasChanged([
            'position',
            'company',
            'description',
            'published_date',
            'deadline_date',
            'location',
            'apply_url',
        ]))) {
            $this->notifyUpdated($job);
        }

        if ($job->getOriginal('status') === Job::STATUS_PUBLISHED && $job->status !== Job::STATUS_PUBLISHED) {
            $this->notifyDeleted($job);
        }
    }

    public function deleted(Job $job): void
    {
        if ($job->status === Job::STATUS_PUBLISHED || $job->getOriginal('status') === Job::STATUS_PUBLISHED) {
            $this->notifyDeleted($job);
        }
    }

    protected function notifyUpdated(Job $job): void
    {
        SubmitUrlToGoogleIndexing::dispatch($this->jobUrl($job), 'URL_UPDATED');
    }

    protected function notifyDeleted(Job $job): void
    {
        SubmitUrlToGoogleIndexing::dispatch($this->jobUrl($job), 'URL_DELETED');
    }

    protected function jobUrl(Job $job): string
    {
        return route('jobs.show', $job, absolute: true);
    }
}
