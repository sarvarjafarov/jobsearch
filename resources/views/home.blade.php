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
    @php
        $partners = ['Aurora','Nimbus','Beacon','ParcelFlow','Evergreen','SimplifiPay'];
        $testimonials = [
            [
                'quote' => 'Jobify helped us fill three critical roles in under a month. The curated feed and resume builder keep candidates ready to move fast.',
                'name' => 'Sasha Patel',
                'role' => 'VP People, Aurora Labs',
            ],
            [
                'quote' => 'I found Nimbus through Jobify and loved how easy it was to track deadlines and apply. The new resume templates are gorgeous.',
                'name' => 'Marcus Lee',
                'role' => 'Product Designer',
            ],
            [
                'quote' => 'As a hiring manager, I rely on Jobify’s clean dashboard to keep the best candidates top-of-mind.',
                'name' => 'Elea Martinez',
                'role' => 'Head of Engineering, Pulse Analytics',
            ],
        ];
    @endphp

    <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5">
        <h2 class="mb-4 text-2xl font-semibold text-body">Search featured roles</h2>
        <form action="{{ route('home') }}" method="GET" class="mb-6 space-y-3">
            <label for="q" class="sr-only">Search</label>
            <div class="flex flex-col gap-3 md:flex-row md:items-center">
                <input
                    type="text"
                    id="q"
                    name="q"
                    value="{{ $query }}"
                    placeholder="Search by role or keyword"
                    class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-base shadow-sm focus:border-primary focus:ring-primary transition-all duration-300 ease-in-out md:flex-1"
                >
                <select name="company" class="w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary md:w-52">
                    <option value="">All companies</option>
                    @foreach($companies as $company)
                        <option value="{{ $company }}" @selected($selectedCompany === $company)>{{ $company }}</option>
                    @endforeach
                </select>
                <select name="sort" class="w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary md:w-44">
                    <option value="latest" @selected($selectedSort === 'latest')>Newest first</option>
                    <option value="soonest" @selected($selectedSort === 'soonest')>Deadline soonest</option>
                </select>
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-primary px-6 py-3 font-medium text-white shadow-md transition-all duration-300 hover:opacity-90 md:w-auto w-full"
                >
                    Apply filters
                </button>
            </div>
        </form>

        <p class="mb-4 text-sm text-slate-500">All listings shown here have been approved by the Jobify team.</p>

        @if ($jobs->isEmpty())
            <p class="text-center text-slate-500">No jobs found. Try a different search.</p>
        @else
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
                        @foreach ($jobs as $job)
                            <tr
                                onclick="window.location='{{ route('jobs.show', $job) }}'"
                                class="cursor-pointer rounded-2xl transition-all duration-300 hover:bg-slate-50"
                            >
                                <td class="px-4 py-4 font-medium text-slate-900">{{ $job->position }}</td>
                                <td class="px-4 py-4 text-slate-600">
                                    @if($job->companyProfile)
                                        <a href="{{ route('companies.show', $job->companyProfile) }}" class="font-medium text-primary hover:underline">
                                            {{ $job->companyProfile->name }}
                                        </a>
                                    @else
                                        {{ $job->company }}
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-slate-500">{{ $job->published_date->format('M d, Y') }}</td>
                                <td class="px-4 py-4 text-slate-500">{{ $job->deadline_date->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="space-y-4 md:hidden">
                @foreach ($jobs as $job)
                    <div class="rounded-3xl border border-slate-100 bg-white p-4 shadow-sm ring-1 ring-black/5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <a href="{{ route('jobs.show', $job) }}" class="text-base font-semibold text-slate-900 hover:text-primary">{{ $job->position }}</a>
                                <p class="mt-1 text-sm text-slate-500">
                                    @if($job->companyProfile)
                                        <a href="{{ route('companies.show', $job->companyProfile) }}" class="text-primary hover:underline">
                                            {{ $job->companyProfile->name }}
                                        </a>
                                    @else
                                        {{ $job->company }}
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('jobs.show', $job) }}" class="rounded-full border border-primary/20 px-3 py-1 text-xs font-semibold text-primary hover:bg-primary/10">View</a>
                        </div>
                        <dl class="mt-4 grid grid-cols-2 gap-4 text-xs uppercase tracking-wide text-slate-400">
                            <div>
                                <dt>Published</dt>
                                <dd class="mt-1 text-sm normal-case text-slate-600">{{ $job->published_date->format('M d, Y') }}</dd>
                            </div>
                            <div>
                                <dt>Deadline</dt>
                                <dd class="mt-1 text-sm normal-case text-slate-600">{{ $job->deadline_date->format('M d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $jobs->onEachSide(1)->links() }}
            </div>
        @endif

        <div id="notify" class="mt-10 rounded-3xl border border-primary/10 bg-white/90 p-6 shadow-inner ring-1 ring-black/5">
            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-primary/60">Notify me</p>
                    <h3 class="mt-3 text-2xl font-semibold text-body">Daily alerts for roles you care about</h3>
                    <p class="mt-3 text-sm text-slate-500">Tell us what you’re looking for and we’ll send a single email each morning. If there are no new roles, we’ll still let you know — no inbox spam.</p>
                    <ul class="mt-4 space-y-2 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <span class="mt-1 h-2 w-2 rounded-full bg-primary"></span>
                            <span>Matches are based on role keywords, company, and location.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 h-2 w-2 rounded-full bg-primary"></span>
                            <span>We bundle every new opening into one clean digest.</span>
                        </li>
                    </ul>
                </div>
                <form method="POST" action="{{ route('alerts.store') }}" class="space-y-4">
                    @csrf
                    @if(session('alert_status'))
                        <div class="rounded-2xl border border-primary/20 bg-primary/5 px-4 py-3 text-sm text-primary">
                            {{ session('alert_status') }}
                        </div>
                    @endif
                    <div>
                        <label for="alert-email" class="text-sm font-medium text-slate-600">Email address*</label>
                        <input
                            type="email"
                            id="alert-email"
                            name="email"
                            required
                            value="{{ old('email') }}"
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary"
                            placeholder="you@example.com"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="alert-keyword" class="text-sm font-medium text-slate-600">Role keyword</label>
                            <input
                                type="text"
                                id="alert-keyword"
                                name="keyword"
                                value="{{ old('keyword') }}"
                                class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary"
                                placeholder="e.g. Product Manager"
                            >
                            @error('keyword')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="alert-company" class="text-sm font-medium text-slate-600">Company</label>
                            <input
                                type="text"
                                id="alert-company"
                                name="company"
                                value="{{ old('company') }}"
                                class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary"
                                placeholder="Optional"
                            >
                            @error('company')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="alert-location" class="text-sm font-medium text-slate-600">Preferred location</label>
                        <input
                            type="text"
                            id="alert-location"
                            name="location"
                            value="{{ old('location') }}"
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary"
                            placeholder="City or Remote"
                        >
                        @error('location')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-primary px-6 py-3 font-semibold text-white shadow-md transition hover:opacity-90"
                    >
                        Create daily alert
                    </button>
                </form>
            </div>
        </div>

        <p class="mt-8 text-center text-sm text-slate-400">© 2025 Jobify — All rights reserved.</p>
    </section>

    <section class="space-y-4">
        <h2 class="text-2xl font-semibold text-body">Why Jobify works</h2>
        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Roles live</p>
                <p class="mt-3 text-3xl font-semibold text-body">{{ number_format($totalJobs) }}</p>
                <p class="text-sm text-slate-500">Freshly curated and verified every morning.</p>
            </div>
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Resume Builder</p>
                <p class="mt-3 text-3xl font-semibold text-body">5+ templates</p>
                <p class="text-sm text-slate-500">Stand out with designer-approved layouts.</p>
                <a href="{{ route('resume.form') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-primary hover:underline">Create a resume →</a>
            </div>
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5 bg-gradient-to-br from-primary/10 to-white">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Trusted by teams</p>
                <p class="mt-3 text-3xl font-semibold text-body">150+</p>
                <p class="text-sm text-slate-500">Hiring partners use Jobify to source talent.</p>
                <a href="{{ route('companies.index') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-primary hover:underline">Meet companies →</a>
            </div>
        </div>
    </section>

    <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5">
        <h2 class="mb-4 text-2xl font-semibold text-body">Partner companies</h2>
        <p class="text-xs uppercase tracking-[0.3em] text-slate-400 text-center">Partner companies</p>
        <div class="mt-4 grid grid-cols-2 gap-6 text-center text-base font-semibold text-slate-500 md:grid-cols-6">
            @foreach($partners as $partner)
                <span class="rounded-2xl border border-slate-100 py-3 shadow-sm">{{ $partner }}</span>
            @endforeach
        </div>
    </section>

    <section class="space-y-4">
        <h2 class="text-2xl font-semibold text-body">People love Jobify</h2>
        <div class="grid gap-6 md:grid-cols-3">
        @foreach ($testimonials as $testimonial)
            <div class="rounded-3xl bg-white p-6 shadow-lg shadow-primary/5 ring-1 ring-black/5">
                <p class="text-lg leading-relaxed text-slate-600">“{{ $testimonial['quote'] }}”</p>
                <p class="mt-4 font-semibold text-body">{{ $testimonial['name'] }}</p>
                <p class="text-sm text-slate-500">{{ $testimonial['role'] }}</p>
            </div>
        @endforeach
        </div>
    </section>

    <section class="space-y-4">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-body">Latest from the blog</h2>
                <p class="text-sm text-slate-500">Insights on hiring trends, candidate journeys, and ways to stand out.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-primary hover:underline">
                View all posts →
            </a>
        </div>

        @if($latestPosts->isNotEmpty())
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($latestPosts as $post)
                    <article class="group flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-black/5 transition hover:-translate-y-1 hover:shadow-lg">
                        @if($post->cover_image)
                            <div class="h-40 w-full overflow-hidden bg-slate-100">
                                <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            </div>
                        @endif
                        <div class="flex flex-1 flex-col p-6">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ optional($post->published_at)->format('M d, Y') ?? 'Coming soon' }}</p>
                            <h3 class="mt-3 text-lg font-semibold text-body">
                                <a href="{{ route('blog.show', $post) }}" class="hover:text-primary transition">{{ $post->title }}</a>
                            </h3>
                            <p class="mt-3 text-sm text-slate-500">
                                {{ \Illuminate\Support\Str::limit($post->excerpt ?? strip_tags($post->body), 120) }}
                            </p>
                            <div class="mt-auto pt-4">
                                <a href="{{ route('blog.show', $post) }}" class="text-sm font-semibold text-primary hover:underline">Read article →</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="rounded-3xl border border-dashed border-slate-200 p-6 text-center text-sm text-slate-500">
                Blog stories are on the way. Check back soon or <a href="{{ route('blog.index') }}" class="font-semibold text-primary hover:underline">visit the blog</a> for more coverage.
            </div>
        @endif
    </section>

    <section class="rounded-3xl bg-gradient-to-r from-primary to-[#7F70FF] p-8 text-white shadow-lg">
        <h2 class="text-2xl font-semibold">Ready to hire or get hired?</h2>
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-white/70">Next step</p>
                <p class="text-3xl font-semibold">Launch your next chapter with Jobify.</p>
                <p class="text-white/80">Explore hundreds of verified opportunities, craft a standout resume, and track applications effortlessly.</p>
            </div>
            <div class="flex flex-col gap-3">
                <a href="{{ route('resume.form') }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3 text-base font-semibold text-primary shadow-md transition hover:opacity-90">Build resume</a>
                <a href="{{ route('companies.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/50 px-6 py-3 text-base font-semibold text-white hover:bg-white/10">Browse companies</a>
            </div>
        </div>
    </section>
@endsection
