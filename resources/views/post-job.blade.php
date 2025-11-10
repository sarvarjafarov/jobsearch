@extends('layout', ['title' => 'Post a job — Jobify'])

@section('content')
    <div class="mx-auto max-w-3xl rounded-3xl bg-white p-8 shadow-md ring-1 ring-black/5">
        <div class="mb-8 space-y-2">
            <h1 class="text-3xl font-semibold text-body">Post a job</h1>
            <p class="text-slate-500">Share your opening with the Jobify community. Jobs go live once our team reviews and approves them.</p>
        </div>

        <form action="{{ route('jobs.store') }}" method="POST" class="space-y-6">
            @csrf

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-600">
                    <p class="font-semibold">Please fix the following:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="position" class="text-sm font-medium text-slate-700">Position</label>
                <input
                    type="text"
                    id="position"
                    name="position"
                    value="{{ old('position') }}"
                    required
                    class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm transition-all duration-300 focus:border-primary focus:ring-primary"
                >
            </div>

            <div>
                <label for="company" class="text-sm font-medium text-slate-700">Company</label>
                <input
                    type="text"
                    id="company"
                    name="company"
                    value="{{ old('company') }}"
                    required
                    class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm transition-all duration-300 focus:border-primary focus:ring-primary"
                >
            </div>

            <div>
                <label for="location" class="text-sm font-medium text-slate-700">Location (optional)</label>
                <input
                    type="text"
                    id="location"
                    name="location"
                    value="{{ old('location') }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm transition-all duration-300 focus:border-primary focus:ring-primary"
                >
            </div>

            <div>
                <label for="apply_url" class="text-sm font-medium text-slate-700">Application URL</label>
                <input
                    type="url"
                    id="apply_url"
                    name="apply_url"
                    value="{{ old('apply_url') }}"
                    required
                    placeholder="https://company.com/careers/apply"
                    class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm transition-all duration-300 focus:border-primary focus:ring-primary"
                >
                <p class="mt-1 text-xs text-slate-400">We'll send candidates directly to this link when they click “Apply”.</p>
            </div>

            <div>
                <label for="description" class="text-sm font-medium text-slate-700">Description</label>
                <textarea
                    id="description"
                    name="description"
                    rows="6"
                    required
                    class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm transition-all duration-300 focus:border-primary focus:ring-primary">{{ old('description') }}</textarea>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="published_date" class="text-sm font-medium text-slate-700">Published Date</label>
                    <input
                        type="date"
                        id="published_date"
                        name="published_date"
                        value="{{ old('published_date') }}"
                        required
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm transition-all duration-300 focus:border-primary focus:ring-primary"
                    >
                </div>
                <div>
                    <label for="deadline_date" class="text-sm font-medium text-slate-700">Deadline Date</label>
                    <input
                        type="date"
                        id="deadline_date"
                        name="deadline_date"
                        value="{{ old('deadline_date') }}"
                        required
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm transition-all duration-300 focus:border-primary focus:ring-primary"
                    >
                </div>
            </div>

            <div class="pt-2">
                <button
                    type="submit"
                    class="inline-flex w-full items-center justify-center rounded-2xl bg-primary px-8 py-4 text-base font-semibold text-white shadow-md transition-all duration-300 hover:opacity-90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary/60"
                >
                    Publish role
                </button>
            </div>
        </form>
    </div>
@endsection
