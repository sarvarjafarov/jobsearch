@php
    $stats = $stats ?? [];
    $recentJobs = $recentJobs ?? collect();
@endphp

<div class="space-y-4">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Sync status</h5>
            <p class="card-text text-muted mb-3">Pulls the latest 50 vacancies from <strong>jobsearch.az</strong> using our built-in scraper.</p>
            <dl class="row">
                <dt class="col-sm-3 text-muted">Imported listings</dt>
                <dd class="col-sm-9">{{ number_format($stats['total'] ?? 0) }}</dd>

                <dt class="col-sm-3 text-muted">Last sync</dt>
                <dd class="col-sm-9">{{ optional($stats['last_sync'])->diffForHumans() ?? 'Never' }}</dd>
            </dl>
            <p class="text-muted mb-0">Command: <code>php artisan jobsearch:sync</code></p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-4">Recent imported roles</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Company</th>
                            <th>Deadline</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentJobs as $job)
                            <tr>
                                <td>{{ $job->position }}</td>
                                <td>{{ $job->company }}</td>
                                <td>{{ optional($job->deadline_date)->format('M d, Y') }}</td>
                                <td>{{ $job->updated_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No imported jobs yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
