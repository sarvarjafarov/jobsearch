@extends('layout', ['title' => 'Jobify — Find your next opportunity'])

@section('hero')
    <div class="bg-gradient-to-r from-primary to-[#7F70FF]">
        <div class="mx-auto max-w-6xl px-6 py-16 text-white">
            <p class="text-xs uppercase tracking-[0.3em] text-white/70">Jobify</p>
            <h1 class="mt-3 text-4xl font-semibold leading-tight">Find your next opportunity — effortlessly.</h1>
            <p class="mt-4 max-w-2xl text-lg text-white/80">Browse open positions, track deadlines, and apply faster. Everything is organized in one clean, modern view inspired by Simplify.jobs.</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5">
        <form action="{{ route('home') }}" method="GET" class="mb-6">
            <label for="q" class="sr-only">Search</label>
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                <input
                    type="text"
                    id="q"
                    name="q"
                    value="{{ $query }}"
                    placeholder="Search by title or company"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-base shadow-sm focus:border-primary focus:ring-primary transition-all duration-300 ease-in-out"
                >
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-primary px-6 py-3 font-medium text-white shadow-md transition-all duration-300 hover:opacity-90"
                >
                    Search
                </button>
            </div>
        </form>

        <p class="mb-4 text-sm text-slate-500">All listings shown here have been approved by the Jobify team.</p>

        <div class="hidden md:block">
            <table class="min-w-full table-auto text-left text-sm text-slate-600">
                <thead>
                    <tr class="text-xs uppercase tracking-wide text-slate-400">
                        <th class="px-4 py-3 font-medium">Position</th>
                        <th class="px-4 py-3 font-medium">Company</th>
                        <th class="px-4 py-3 font-medium">Published</th>
                        <th class="px-4 py-3 font-medium">Deadline</th>
                    </tr>
                </thead>
                <tbody class="text-base text-body">
                    @forelse ($jobs as $job)
                        <tr
                            onclick="window.location='{{ route('jobs.show', $job) }}'"
                            class="cursor-pointer rounded-2xl transition-all duration-300 hover:bg-slate-50"
                        >
                            <td class="px-4 py-4 font-medium text-slate-900">{{ $job->position }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $job->company }}</td>
                            <td class="px-4 py-4 text-slate-500">{{ $job->published_date->format('M d, Y') }}</td>
                            <td class="px-4 py-4 text-slate-500">{{ $job->deadline_date->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-slate-500">No jobs found. Try a different search.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="space-y-4 md:hidden">
            @forelse ($jobs as $job)
                <a href="{{ route('jobs.show', $job) }}" class="block rounded-2xl border border-slate-100 p-4 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/50 hover:shadow-md">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-base font-semibold text-slate-900">{{ $job->position }}</p>
                            <p class="text-sm text-slate-500">{{ $job->company }}</p>
                        </div>
                        <span class="text-xs font-medium text-primary">View</span>
                    </div>
                    <dl class="mt-4 grid grid-cols-2 gap-4 text-sm text-slate-500">
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">Published</dt>
                            <dd class="mt-1">{{ $job->published_date->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-slate-400">Deadline</dt>
                            <dd class="mt-1">{{ $job->deadline_date->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </a>
            @empty
                <p class="text-center text-slate-500">No jobs found. Try a different search.</p>
            @endforelse
        </div>

        <p class="mt-8 text-center text-sm text-slate-400">© 2025 Jobify — All rights reserved.</p>
    </div>
@endsection
