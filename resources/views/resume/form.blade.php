@extends('layout', ['title' => 'Resume Builder — Jobify'])

@section('hero')
    <div class="bg-gradient-to-r from-primary to-[#7F70FF]">
        <div class="mx-auto max-w-6xl px-6 py-16 text-white">
            <p class="text-xs uppercase tracking-[0.3em] text-white/70">Resume Creator</p>
            <h1 class="mt-3 text-4xl font-semibold leading-tight">Generate a polished resume in minutes.</h1>
            <p class="mt-4 max-w-3xl text-lg text-white/80">Fill in your details once, pick a template, and Jobify will deliver a downloadable PDF with modern styling.</p>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ route('resume.generate') }}" method="POST" class="space-y-10 rounded-3xl bg-white p-8 shadow-sm ring-1 ring-black/5">
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

        <section class="space-y-4">
            <h2 class="text-2xl font-semibold text-body">Personal Information</h2>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-slate-600" for="full_name">Full name</label>
                    <input id="full_name" name="full_name" value="{{ old('full_name') }}" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600" for="headline">Professional headline</label>
                    <input id="headline" name="headline" value="{{ old('headline') }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary" placeholder="e.g. Senior Product Designer">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600" for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600" for="phone">Phone</label>
                    <input id="phone" name="phone" value="{{ old('phone') }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600" for="location">Location</label>
                    <input id="location" name="location" value="{{ old('location') }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                </div>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600" for="summary">Professional summary</label>
                <textarea id="summary" name="summary" rows="4" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary" placeholder="2–3 sentences about your impact">{{ old('summary') }}</textarea>
            </div>
            <div>
                <label class="text-sm font-medium text-slate-600" for="skills">Skills (comma or line separated)</label>
                <textarea id="skills" name="skills" rows="3" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary" placeholder="Product strategy, UX Research, Figma, Motion Design">{{ old('skills') }}</textarea>
            </div>
        </section>

        <section class="space-y-4">
            <h2 class="text-2xl font-semibold text-body">Experience</h2>
            <p class="text-sm text-slate-500">Add up to three roles. Lines in “Highlights” become bullet points.</p>
            @for ($i = 0; $i < 3; $i++)
                <div class="rounded-2xl border border-slate-100 p-4">
                    <p class="text-sm font-semibold text-slate-500 mb-3">Role {{ $i + 1 }}</p>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-slate-600" for="exp-role-{{ $i }}">Job title</label>
                            <input id="exp-role-{{ $i }}" name="experience[{{ $i }}][role]" value="{{ old("experience.$i.role") }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-600" for="exp-company-{{ $i }}">Company</label>
                            <input id="exp-company-{{ $i }}" name="experience[{{ $i }}][company]" value="{{ old("experience.$i.company") }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-600" for="exp-location-{{ $i }}">Location</label>
                            <input id="exp-location-{{ $i }}" name="experience[{{ $i }}][location]" value="{{ old("experience.$i.location") }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-sm font-medium text-slate-600" for="exp-start-{{ $i }}">Start</label>
                                <input id="exp-start-{{ $i }}" name="experience[{{ $i }}][start]" value="{{ old("experience.$i.start") }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                            </div>
                            <div>
                                <label class="text-sm font-medium text-slate-600" for="exp-end-{{ $i }}">End</label>
                                <input id="exp-end-{{ $i }}" name="experience[{{ $i }}][end]" value="{{ old("experience.$i.end") }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary" placeholder="Present">
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="text-sm font-medium text-slate-600" for="exp-details-{{ $i }}">Highlights</label>
                        <textarea id="exp-details-{{ $i }}" name="experience[{{ $i }}][details]" rows="3" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary" placeholder="- Led redesign...\n- Shipped feature X">{{ old("experience.$i.details") }}</textarea>
                    </div>
                </div>
            @endfor
        </section>

        <section class="space-y-4">
            <h2 class="text-2xl font-semibold text-body">Education</h2>
            @for ($i = 0; $i < 2; $i++)
                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="text-sm font-medium text-slate-600" for="edu-school-{{ $i }}">School</label>
                        <input id="edu-school-{{ $i }}" name="education[{{ $i }}][school]" value="{{ old("education.$i.school") }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600" for="edu-degree-{{ $i }}">Degree / Focus</label>
                        <input id="edu-degree-{{ $i }}" name="education[{{ $i }}][degree]" value="{{ old("education.$i.degree") }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600" for="edu-years-{{ $i }}">Years</label>
                        <input id="edu-years-{{ $i }}" name="education[{{ $i }}][years]" value="{{ old("education.$i.years") }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary" placeholder="2016 — 2020">
                    </div>
                </div>
            @endfor
        </section>

        <section class="space-y-4">
            <h2 class="text-2xl font-semibold text-body">Projects & Links</h2>
            @for ($i = 0; $i < 2; $i++)
                <div class="rounded-2xl border border-slate-100 p-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-slate-600" for="project-name-{{ $i }}">Project name</label>
                            <input id="project-name-{{ $i }}" name="projects[{{ $i }}][name]" value="{{ old("projects.$i.name") }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-600" for="project-link-{{ $i }}">Link (optional)</label>
                            <input id="project-link-{{ $i }}" name="links[{{ $i }}][url]" value="{{ old("links.$i.url") }}" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary" placeholder="https://">
                            <input type="hidden" name="links[{{ $i }}][label]" value="{{ old("projects.$i.name") }}">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="text-sm font-medium text-slate-600" for="project-description-{{ $i }}">Description</label>
                        <textarea id="project-description-{{ $i }}" name="projects[{{ $i }}][description]" rows="3" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary">{{ old("projects.$i.description") }}</textarea>
                    </div>
                </div>
            @endfor
        </section>

        <section class="space-y-4">
            <h2 class="text-2xl font-semibold text-body">Choose a template</h2>
            <div class="grid gap-4 md:grid-cols-3">
                @foreach ($templates as $key => $label)
                    <label class="block cursor-pointer rounded-2xl border {{ old('template', 'modern') === $key ? 'border-primary bg-primary/5' : 'border-slate-200' }} p-4 transition hover:border-primary">
                        <div class="flex items-center justify-between">
                            <span class="text-base font-semibold text-body">{{ $label }}</span>
                            <input type="radio" name="template" value="{{ $key }}" class="text-primary focus:ring-primary" @checked(old('template', 'modern') === $key)>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">Clean typography and spacing, optimized for PDF.</p>
                    </label>
                @endforeach
            </div>
        </section>

        <button type="submit" class="w-full rounded-2xl bg-primary px-6 py-4 text-base font-semibold text-white shadow-md transition hover:opacity-90">Download PDF</button>
    </form>
@endsection
