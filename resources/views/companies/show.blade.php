@extends('layout', ['title' => $company->name . ' — Company Profile'])
@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="space-y-10">
        <div class="rounded-3xl bg-white p-8 shadow-md ring-1 ring-black/5">
            <div class="flex flex-col gap-6 md:flex-row md:items-center">
                <img src="{{ $company->logo_url ?? 'https://placehold.co/96x96?text='.urlencode($company->name[0]) }}" alt="{{ $company->name }} logo" class="h-24 w-24 rounded-3xl bg-slate-100 object-cover">
                <div class="flex-1">
                    <p class="text-sm uppercase tracking-wide text-slate-400">{{ $company->industry ?? 'Industry' }}</p>
                    <h1 class="mt-1 text-4xl font-semibold text-body">{{ $company->name }}</h1>
                    <div class="mt-3 flex flex-wrap gap-4 text-sm text-slate-500">
                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-700">
                            ★ {{ number_format($company->rating ?? data_get($averages, 'overall', 0), 1) }} Rating
                        </span>
                        <span>{{ $company->size ?? 'Growing team' }}</span>
                        <span>{{ $company->headquarters ?? 'Remote' }}</span>
                        @if($company->founded_year)
                            <span>Founded {{ $company->founded_year }}</span>
                        @endif
                    </div>
                    <div class="mt-4 flex flex-wrap gap-4 text-sm">
                        @if($company->website_url)
                            <a href="{{ $company->website_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 px-4 py-2 font-medium text-primary hover:border-primary/50">
                                Visit Website →
                            </a>
                        @endif
                        <a href="{{ route('companies.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-transparent px-4 py-2 font-medium text-slate-500 hover:text-primary">
                            ← Back to companies
                        </a>
                    </div>
                </div>
            </div>
            <p class="mt-6 text-lg leading-relaxed text-slate-600">{{ $company->description }}</p>
            @if($company->perks)
                <div class="mt-6">
                    <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">Why people love working here</p>
                    <div class="mt-3 flex flex-wrap gap-3">
                        @foreach ($company->perks as $perk)
                            <span class="rounded-full bg-slate-100 px-4 py-1 text-sm font-medium text-slate-600">{{ $perk }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-black/5">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-body">What people are saying</h2>
                    <p class="text-sm text-slate-500">{{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</p>
                </div>
                <a href="#share-review" class="rounded-2xl bg-primary px-5 py-2 text-sm font-semibold text-white shadow-md transition hover:opacity-90">Share your experience</a>
            </div>

            @if($reviewCount)
                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl border border-slate-100 p-6">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Overall rating</p>
                        <p class="mt-2 text-4xl font-semibold text-body">{{ number_format(data_get($averages, 'overall', 0), 1) }}</p>
                        <p class="text-sm text-slate-500">based on {{ $reviewCount }} reviews</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 p-6">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Would recommend</p>
                        <p class="mt-2 text-4xl font-semibold text-body">{{ $recommendRate ?? 0 }}%</p>
                        <p class="text-sm text-slate-500">say they’d refer a friend</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 p-6">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Culture pulse</p>
                        <div class="mt-2 grid grid-cols-2 gap-2 text-sm text-slate-600">
                            <div>Culture <span class="font-semibold">{{ number_format(data_get($averages, 'culture', 0), 1) }}</span></div>
                            <div>Comp <span class="font-semibold">{{ number_format(data_get($averages, 'compensation', 0), 1) }}</span></div>
                            <div>Leadership <span class="font-semibold">{{ number_format(data_get($averages, 'leadership', 0), 1) }}</span></div>
                            <div>Work-Life <span class="font-semibold">{{ number_format(data_get($averages, 'work_life', 0), 1) }}</span></div>
                            <div>Growth <span class="font-semibold">{{ number_format(data_get($averages, 'growth', 0), 1) }}</span></div>
                        </div>
                    </div>
                </div>
            @else
                <p class="mt-6 rounded-2xl bg-slate-50 p-6 text-center text-slate-500">Be the first to review this company.</p>
            @endif
        </div>

        <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-black/5">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-body">Open roles</h2>
                    <p class="text-sm text-slate-500">Direct from the Jobify board</p>
                </div>
                <span class="text-sm font-semibold text-slate-500">{{ $company->publishedJobs->count() }} {{ Str::plural('role', $company->publishedJobs->count()) }}</span>
            </div>

            <div class="mt-6 space-y-4">
                @forelse ($company->publishedJobs as $job)
                    <a href="{{ route('jobs.show', $job) }}" class="flex flex-col gap-4 rounded-2xl border border-slate-100 p-5 transition hover:-translate-y-0.5 hover:border-primary/50 hover:shadow-md md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-lg font-semibold text-body">{{ $job->position }}</p>
                            <p class="text-sm text-slate-500">{{ $job->location ?: 'Remote' }}</p>
                        </div>
                        <div class="text-sm text-slate-500">
                            <p>Published {{ $job->published_date->format('M d, Y') }}</p>
                            <p>Deadline {{ $job->deadline_date->format('M d, Y') }}</p>
                        </div>
                    </a>
                @empty
                    <p class="rounded-2xl bg-slate-50 p-6 text-center text-slate-500">No live roles at the moment. Check back soon!</p>
                @endforelse
            </div>
        </div>

        <div id="reviews" class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-black/5">
            <h2 class="text-2xl font-semibold text-body">Employee reviews</h2>
            <p class="text-sm text-slate-500">Real stories from people who’ve worked here.</p>

            <div class="mt-6 space-y-6">
                @forelse ($reviews as $review)
                    <div class="rounded-2xl border border-slate-100 p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-base font-semibold text-body">{{ $review->reviewer_name ?? 'Anonymous' }}</p>
                                <p class="text-sm text-slate-500">{{ $review->reviewer_role ?? 'Member of the team' }} · {{ $review->employment_type ?? 'Employment type not disclosed' }}</p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-sm font-semibold text-primary">
                                ★ {{ number_format($review->overall_rating, 1) }}
                            </span>
                        </div>
                        <dl class="mt-4 grid gap-3 text-sm text-slate-600 sm:grid-cols-2 lg:grid-cols-3">
                            <div>Culture: <span class="font-semibold">{{ $review->culture_rating }}/5</span></div>
                            <div>Compensation: <span class="font-semibold">{{ $review->compensation_rating }}/5</span></div>
                            <div>Leadership: <span class="font-semibold">{{ $review->leadership_rating }}/5</span></div>
                            <div>Work-life: <span class="font-semibold">{{ $review->work_life_rating }}/5</span></div>
                            <div>Growth: <span class="font-semibold">{{ $review->growth_rating }}/5</span></div>
                            <div>Recommend: <span class="font-semibold">{{ $review->would_recommend ? 'Yes' : 'No' }}</span></div>
                        </dl>
                        @if($review->highlights)
                            <p class="mt-4 text-sm text-slate-600"><span class="font-semibold text-body">Highlights:</span> {{ $review->highlights }}</p>
                        @endif
                        @if($review->challenges)
                            <p class="mt-2 text-sm text-slate-600"><span class="font-semibold text-body">Challenges:</span> {{ $review->challenges }}</p>
                        @endif
                        @if($review->advice)
                            <p class="mt-2 text-sm text-slate-600"><span class="font-semibold text-body">Advice:</span> {{ $review->advice }}</p>
                        @endif
                        <p class="mt-3 text-xs uppercase tracking-wider text-slate-400">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="rounded-2xl bg-slate-50 p-6 text-center text-slate-500">No reviews yet. Be the first to share your perspective.</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $reviews->fragment('reviews')->links() }}
            </div>
        </div>

        <div id="share-review" class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-black/5">
            <h2 class="text-2xl font-semibold text-body">Share your experience</h2>
            <p class="text-sm text-slate-500">Your responses are anonymous and help other candidates understand life at {{ $company->name }}.</p>

            <form action="{{ route('companies.reviews.store', $company) }}" method="POST" class="mt-6 space-y-6">
                @csrf

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-slate-600" for="reviewer_name">Name (optional)</label>
                        <input class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary" id="reviewer_name" name="reviewer_name" value="{{ old('reviewer_name') }}">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600" for="reviewer_role">Role / Team</label>
                        <input class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary" id="reviewer_role" name="reviewer_role" value="{{ old('reviewer_role') }}">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600" for="employment_type">Employment type</label>
                        <input class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary" id="employment_type" name="employment_type" value="{{ old('employment_type') }}" placeholder="Full-time, contractor, intern...">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600" for="overall_rating">Overall rating</label>
                        <select id="overall_rating" name="overall_rating" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                            @for ($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" @selected(old('overall_rating', 5) == $i)>{{ $i }} - {{ ['Poor','Fair','OK','Great','Outstanding'][$i-1] ?? $i }}</option>
                            @endfor
                        </select>
                        @error('overall_rating')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach (['culture' => 'Culture', 'compensation' => 'Compensation', 'leadership' => 'Leadership', 'work_life' => 'Work-life balance', 'growth' => 'Growth opportunities'] as $field => $label)
                        <div>
                            <label class="text-sm font-medium text-slate-600" for="{{ $field }}_rating">{{ $label }}</label>
                            <select id="{{ $field }}_rating" name="{{ $field }}_rating" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" @selected(old($field.'_rating', 5) == $i)>{{ $i }}</option>
                                @endfor
                            </select>
                            @error($field.'_rating')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="md:col-span-3">
                        <label class="text-sm font-medium text-slate-600" for="highlights">Highlights</label>
                        <textarea id="highlights" name="highlights" rows="3" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">{{ old('highlights') }}</textarea>
                    </div>
                    <div class="md:col-span-3">
                        <label class="text-sm font-medium text-slate-600" for="challenges">Challenges</label>
                        <textarea id="challenges" name="challenges" rows="3" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">{{ old('challenges') }}</textarea>
                    </div>
                    <div class="md:col-span-3">
                        <label class="text-sm font-medium text-slate-600" for="advice">Advice to leadership</label>
                        <textarea id="advice" name="advice" rows="3" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">{{ old('advice') }}</textarea>
                    </div>
                </div>

                <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="would_recommend" value="1" @checked(old('would_recommend', true)) class="rounded border-slate-300 text-primary focus:ring-primary">
                    I would recommend {{ $company->name }} to a friend
                </label>

                <button type="submit" class="w-full rounded-2xl bg-primary px-6 py-3 text-base font-semibold text-white shadow-md transition hover:opacity-90">Submit review</button>
            </form>
        </div>
    </div>
@endsection
