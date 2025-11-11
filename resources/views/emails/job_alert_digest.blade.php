<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Jobify Daily Digest</title>
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                color: #111827;
                margin: 0;
                padding: 0;
                background-color: #f4f5f7;
            }
            .wrapper {
                max-width: 640px;
                margin: 0 auto;
                padding: 24px;
            }
            .card {
                background: #ffffff;
                border-radius: 18px;
                padding: 32px;
                box-shadow: 0 15px 35px rgba(15, 23, 42, 0.08);
            }
            h1 {
                margin-top: 0;
                font-size: 24px;
            }
            .job {
                border: 1px solid #e5e7eb;
                border-radius: 14px;
                padding: 16px 20px;
                margin-bottom: 16px;
            }
            .job a {
                color: #5F4DEE;
                font-weight: 600;
                text-decoration: none;
            }
            .footer {
                margin-top: 24px;
                font-size: 13px;
                color: #6b7280;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="card">
                <p style="text-transform: uppercase; letter-spacing: 0.3em; color: #a5b4fc; font-size: 12px;">Daily digest</p>
                <h1>
                    {{ $jobs->count() ? 'Here are the latest openings for you' : 'Unfortunately, no fresh roles today' }}
                </h1>
                <p style="margin-top: 0;">
                    Date range: {{ $rangeStart->timezone(config('app.timezone'))->format('M d') }} &mdash; {{ $rangeEnd->timezone(config('app.timezone'))->format('M d, Y') }}.
                </p>

                @if($jobs->isNotEmpty())
                    @foreach($jobs as $job)
                        <div class="job">
                            <p style="margin: 0; font-size: 16px; font-weight: 600;">
                                {{ $job->position }}
                                <span style="font-weight: 400; color: #6b7280;">at {{ $job->company }}</span>
                            </p>
                            <p style="margin: 8px 0 0; font-size: 14px; color: #4b5563;">
                                Published: {{ optional($job->published_date)->format('M d, Y') ?? '—' }} · Deadline: {{ optional($job->deadline_date)->format('M d, Y') ?? '—' }}
                            </p>
                            <p style="margin: 8px 0 0;">
                                <a href="{{ route('jobs.show', $job) }}" target="_blank" rel="noopener">View details</a>
                                @if($job->apply_url)
                                    &nbsp;|&nbsp;
                                    <a href="{{ $job->apply_url }}" target="_blank" rel="noopener">Apply</a>
                                @endif
                            </p>
                        </div>
                    @endforeach
                @else
                    <p>Unfortunately, there weren’t any new openings that matched your filters. We’ll keep watching and send you fresh leads tomorrow.</p>
                @endif

                <p style="margin-top: 24px; font-size: 14px; color: #6b7280;">
                    You are receiving this email because you subscribed to Jobify daily alerts ({{ $alert->email }}). To pause notifications, contact our team.
                </p>
            </div>
            <p class="footer">© {{ now()->year }} Jobify · Built with Laravel & Tailwind</p>
        </div>
    </body>
</html>
