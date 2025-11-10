@extends('layout', ['title' => 'Discover Companies — Jobify'])
@php use Illuminate\Support\Str; @endphp

@section('hero')
    <div class="bg-gradient-to-r from-primary to-[#7F70FF]">
        <div class="mx-auto max-w-6xl px-6 py-16 text-white">
            <p class="text-xs uppercase tracking-[0.3em] text-white/70">Companies</p>
            <h1 class="mt-3 text-4xl font-semibold leading-tight">Explore teams hiring right now.</h1>
            <p class="mt-4 max-w-3xl text-lg text-white/80">Browse culture profiles, learn what they build, and jump straight to roles that match your goals.</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-8">
        <form class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5" method="GET" action="{{ route('companies.index') }}">
            <div class="grid gap-4 md:grid-cols-3">
                <div class="md:col-span-2">
                    <label for="q" class="text-sm font-medium text-slate-600">Search companies</label>
                    <input
                        id="q"
                        name="q"
                        value="{{ $query }}"
                        placeholder="Search by name, keyword, or location"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary"
                        type="text"
                    >
                </div>
                <div>
                    <label for="industry" class="text-sm font-medium text-slate-600">Filter by industry</label>
                    <select
                        id="industry"
                        name="industry"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary"
                    >
                        <option value="">All industries</option>
                        @foreach ($industries as $industry)
                            <option value="{{ $industry }}" @selected($selectedIndustry === $industry)>{{ $industry }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap gap-3">
                <button type="submit" class="rounded-2xl bg-primary px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:opacity-90">Search</button>
                @if($query || $selectedIndustry)
                    <a href="{{ route('companies.index') }}" class="text-sm font-medium text-slate-500 hover:text-primary transition">Clear filters</a>
                @endif
            </div>
        </form>

        <div class="grid gap-6 md:grid-cols-2">
            @forelse ($companies as $company)
                <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5 transition hover:-translate-y-0.5 hover:shadow-lg">
                    <div class="flex items-start gap-4">
                        <img src="{{ $company->logo_url ?? 'https://placehold.co/72x72?text='.urlencode($company->name[0]) }}" alt="{{ $company->name }} logo" class="h-16 w-16 rounded-2xl bg-slate-100 object-cover">
                        <div>
                            <a href="{{ route('companies.show', $company) }}" class="text-xl font-semibold text-slate-900 hover:text-primary transition">{{ $company->name }}</a>
                            <p class="text-sm text-slate-500">{{ $company->industry ?? 'Industry not specified' }}</p>
                            <div class="mt-2 flex items-center gap-3 text-sm text-slate-500">
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-emerald-700 text-xs font-semibold">
                                    ★ {{ number_format($company->rating, 1) }}
                                </span>
                                <span>{{ $company->headquarters ?? 'Remote' }}</span>
                                <span>{{ $company->size ?? 'Growing team' }}</span>
                            </div>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-slate-600 line-clamp-3">{{ $company->description }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-sm text-slate-500">{{ $company->published_jobs_count }} open {{ Str::plural('role', $company->published_jobs_count) }}</span>
                        <a href="{{ route('companies.show', $company) }}" class="text-sm font-semibold text-primary hover:underline">View profile →</a>
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 rounded-3xl bg-white p-12 text-center text-slate-500 shadow-sm">
                    No companies match your filters yet. Try another search.
                </div>
            @endforelse
        </div>

        {{ $companies->links() }}
    </div>
@endsection
