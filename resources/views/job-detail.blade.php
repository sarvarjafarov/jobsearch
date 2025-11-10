@extends('layout', ['title' => $job->position . ' — ' . $job->company])

@section('content')
    <div class="mx-auto max-w-3xl rounded-3xl bg-white p-8 shadow-md ring-1 ring-black/5">
        <p class="text-sm font-medium text-primary">{{ $job->company }}</p>
        <h1 class="mt-2 text-3xl font-semibold text-body">{{ $job->position }}</h1>
        <p class="mt-2 text-slate-500">{{ $job->location ?: 'Remote' }}</p>

        <dl class="mt-6 grid gap-4 text-sm text-slate-500 sm:grid-cols-2">
            <div class="rounded-2xl border border-slate-100 p-4">
                <dt class="text-xs uppercase tracking-wide text-slate-400">Published</dt>
                <dd class="mt-1 text-base text-body">{{ $job->published_date->format('M d, Y') }}</dd>
            </div>
            <div class="rounded-2xl border border-slate-100 p-4">
                <dt class="text-xs uppercase tracking-wide text-slate-400">Deadline</dt>
                <dd class="mt-1 text-base text-body">{{ $job->deadline_date->format('M d, Y') }}</dd>
            </div>
        </dl>

        <div class="mt-8 space-y-4 text-lg leading-relaxed text-slate-600">
            {!! nl2br(e($job->description)) !!}
        </div>

        <div class="mt-8 flex flex-wrap items-center gap-4">
            @if ($job->apply_url)
                <a
                    href="{{ $job->apply_url }}"
                    target="_blank"
                    rel="noopener"
                    class="inline-flex items-center justify-center rounded-2xl bg-primary px-8 py-3 font-semibold text-white shadow-md transition-all duration-300 hover:opacity-90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary/60"
                >
                    Apply Now
                </a>
            @else
                <span class="inline-flex items-center justify-center rounded-2xl bg-slate-200 px-8 py-3 font-semibold text-slate-500">
                    Apply link coming soon
                </span>
            @endif
            <a href="{{ route('home') }}" class="text-sm font-medium text-primary hover:underline">← Back to all jobs</a>
        </div>
    </div>
@endsection
