<div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <p class="text-muted text-uppercase small mb-1">Pending review</p>
                    <h3 class="fw-semibold mb-0">{{ data_get($stats, 'pending', 0) }}</h3>
                    <p class="text-muted small mb-0">Submissions waiting for approval.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <p class="text-muted text-uppercase small mb-1">Live roles</p>
                    <h3 class="fw-semibold mb-0">{{ data_get($stats, 'published', 0) }}</h3>
                    <p class="text-muted small mb-0">Visible on the public job board.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body">
                    <p class="text-muted text-uppercase small mb-1">Closing soon</p>
                    <h3 class="fw-semibold mb-0">{{ data_get($stats, 'upcoming_deadlines', 0) }}</h3>
                    <p class="text-muted small mb-0">Deadlines within the next 14 days.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <div>
                <h6 class="mb-0">Latest pending jobs</h6>
                <small class="text-muted">Review and publish new submissions.</small>
            </div>
            <a href="{{ route('platform.jobs.list') }}" class="btn btn-outline-primary btn-sm">Manage jobs</a>
        </div>
        <div class="list-group list-group-flush">
            @forelse($pendingJobs as $job)
                <a href="{{ route('platform.jobs.edit', $job) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">{{ $job->position }}</div>
                        <small class="text-muted">{{ $job->company }}</small>
                    </div>
                    <small class="text-muted">{{ $job->created_at?->diffForHumans() }}</small>
                </a>
            @empty
                <div class="p-4 text-center text-muted">
                    All caught up — no pending jobs right now.
                </div>
            @endforelse
        </div>
    </div>
</div>
