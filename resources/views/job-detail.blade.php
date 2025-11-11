@extends('layout', ['title' => $job->position . ' — ' . $job->company])

@section('content')
    <div class="mx-auto max-w-3xl rounded-3xl bg-white p-8 shadow-md ring-1 ring-black/5">
        <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500">
            @if($job->companyProfile)
                <a href="{{ route('companies.show', $job->companyProfile) }}" class="text-primary font-semibold hover:underline">
                    {{ $job->companyProfile->name }}
                </a>
            @else
                <span class="text-primary font-semibold">{{ $job->company }}</span>
            @endif
            <span class="text-slate-400">•</span>
            <span>{{ $job->location ?: 'Remote' }}</span>
        </div>
        <h1 class="mt-2 text-3xl font-semibold text-body">{{ $job->position }}</h1>

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

        @php
            $descriptionHtml = \App\Support\JobDescriptionFormatter::toHtml($job->description);
        @endphp

        @if($descriptionHtml)
            <div class="mt-8 rounded-3xl border border-slate-100 bg-gradient-to-br from-white to-slate-50 p-6 shadow-sm">
                <h2 class="text-2xl font-semibold text-body">Role snapshot</h2>
                <div class="job-description prose prose-slate mt-4 max-w-none text-base leading-relaxed marker:text-primary prose-ul:my-4 prose-li:ml-0">
                    {!! $descriptionHtml !!}
                </div>
            </div>
        @endif

        @if($job->companyProfile)
            <div class="mt-8 rounded-2xl border border-slate-100 p-6">
                <p class="text-sm uppercase tracking-wide text-slate-400">Company snapshot</p>
                <div class="mt-3 flex flex-wrap gap-4 text-sm text-slate-500">
                    @if($job->companyProfile->industry)
                        <span>{{ $job->companyProfile->industry }}</span>
                    @endif
                    @if($job->companyProfile->headquarters)
                        <span>{{ $job->companyProfile->headquarters }}</span>
                    @endif
                    @if($job->companyProfile->size)
                        <span>{{ $job->companyProfile->size }}</span>
                    @endif
                </div>
                <a href="{{ route('companies.show', $job->companyProfile) }}" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-primary hover:underline">
                    View full company profile →
                </a>
            </div>
        @endif

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
