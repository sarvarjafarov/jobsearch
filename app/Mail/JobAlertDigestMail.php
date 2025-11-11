<?php

namespace App\Mail;

use App\Models\JobAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class JobAlertDigestMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public JobAlert $alert,
        public Collection $jobs,
        public \Carbon\Carbon $rangeStart,
        public \Carbon\Carbon $rangeEnd
    ) {
    }

    public function build(): self
    {
        $count = $this->jobs->count();
        $roleLabel = $count === 1 ? 'role' : 'roles';
        $subject = $count > 0
            ? "Jobify found {$count} new {$roleLabel} for you"
            : 'Jobify daily update — unfortunately no new roles today';

        return $this
            ->subject($subject)
            ->view('emails.job_alert_digest');
    }
}
