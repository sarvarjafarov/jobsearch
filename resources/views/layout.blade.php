<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @php
            $seo = $seoData ?? [];
            $pageTitle = $seo['title'] ?? ($title ?? config('app.name'));
        @endphp
        <title>{{ $pageTitle }}</title>
        @if(!empty($seo['description']))
            <meta name="description" content="{{ $seo['description'] }}">
        @endif
        @if(!empty($seo['keywords']))
            <meta name="keywords" content="{{ $seo['keywords'] }}">
        @endif
        @if(!empty($seo['canonical']))
            <link rel="canonical" href="{{ $seo['canonical'] }}">
        @endif
        @if(!empty($seo['noindex']))
            <meta name="robots" content="noindex,nofollow">
        @endif
        <meta property="og:site_name" content="{{ $seo['site_name'] ?? config('app.name') }}">
        <meta property="og:title" content="{{ $seo['og_title'] ?? $pageTitle }}">
        @if(!empty($seo['og_description']))
            <meta property="og:description" content="{{ $seo['og_description'] }}">
        @endif
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        @if(!empty($seo['og_image']))
            <meta property="og:image" content="{{ $seo['og_image'] }}">
        @endif
        @if(!empty($seo['twitter_image']))
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:image" content="{{ $seo['twitter_image'] }}">
        @endif
        @if(!empty($seo['schema']))
            <script type="application/ld+json">{!! json_encode($seo['schema'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>
        @endif
        @if(!empty($seo['favicon']))
            <link rel="icon" href="{{ $seo['favicon'] }}">
        @endif
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#5F4DEE',
                            background: '#F9FAFB',
                            body: '#111827',
                        },
                        fontFamily: {
                            inter: ['Inter', 'sans-serif'],
                        },
                    },
                },
            };
        </script>
    </head>
    <body class="bg-background text-body font-inter antialiased">
        <header class="fixed top-0 inset-x-0 z-50 bg-white/95 backdrop-blur shadow-sm">
            <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 md:px-6">
                <a href="{{ route('home') }}" class="text-2xl font-semibold text-primary tracking-tight">Jobify</a>
                <button
                    id="mobile-nav-toggle"
                    class="inline-flex items-center rounded-full border border-primary/20 p-2 text-primary transition hover:bg-primary/10 md:hidden"
                    aria-label="Toggle navigation"
                >
                    <svg id="mobile-nav-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="hidden items-center gap-6 text-sm font-medium md:flex">
                    <a href="{{ route('home') }}" class="transition hover:text-primary">Home</a>
                    <a href="{{ route('companies.index') }}" class="transition hover:text-primary">Companies</a>
                    <a href="{{ route('blog.index') }}" class="transition hover:text-primary">Blog</a>
                    <a href="{{ route('resume.form') }}" class="transition hover:text-primary">Resume Builder</a>
                    <a href="{{ route('jobs.create') }}" class="transition hover:text-primary">Post Job</a>
                    <a href="#contact" class="transition hover:text-primary">Contact</a>
                </div>
            </nav>
            <div id="mobile-nav" class="mx-auto block max-w-6xl px-4 pb-4 md:hidden">
                <div class="rounded-3xl border border-slate-100 bg-white p-4 shadow-sm space-y-3 overflow-hidden max-h-0 opacity-0 pointer-events-none transition-all duration-300">
                    <a href="{{ route('home') }}" class="block rounded-2xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-primary/5">Home</a>
                    <a href="{{ route('companies.index') }}" class="block rounded-2xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-primary/5">Companies</a>
                    <a href="{{ route('blog.index') }}" class="block rounded-2xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-primary/5">Blog</a>
                    <a href="{{ route('resume.form') }}" class="block rounded-2xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-primary/5">Resume Builder</a>
                    <a href="{{ route('jobs.create') }}" class="block rounded-2xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-primary/5">Post Job</a>
                    <a href="#contact" class="block rounded-2xl px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-primary/5">Contact</a>
                </div>
            </div>
        </header>

        @hasSection('hero')
            <section class="pt-24">
                @yield('hero')
            </section>
        @else
            <div class="pt-24"></div>
        @endif

        <main class="mx-auto max-w-6xl px-6 py-16 space-y-8">
            @if (session('status'))
                <div class="rounded-2xl border border-primary/20 bg-white p-4 text-sm text-body shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </main>

        <section id="contact" class="bg-white py-12 px-4">
            <div class="mx-auto max-w-3xl rounded-3xl border border-primary/15 bg-white p-8 text-center shadow-lg">
                <p class="text-xs uppercase tracking-[0.3em] text-primary/60">Contact</p>
                <h2 class="mt-3 text-2xl font-semibold text-body md:text-3xl">Let’s build hiring that feels effortless.</h2>
                <p class="mt-3 text-base text-slate-600">Need help curating roles or customizing Jobify for your team? Email <a class="font-medium text-primary" href="mailto:hello@jobify.com">hello@jobify.com</a>.</p>
            </div>
        </section>

        <footer class="bg-background py-6 text-center text-sm text-slate-500">
            Built with Laravel & Tailwind CSS.
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const toggle = document.getElementById('mobile-nav-toggle');
                const panel = document.querySelector('#mobile-nav > div');
                if (toggle && panel) {
                    toggle.setAttribute('aria-expanded', 'false');

                    toggle.addEventListener('click', function () {
                        const isOpen = panel.classList.contains('max-h-96');

                        if (isOpen) {
                            panel.classList.remove('max-h-96', 'opacity-100');
                            panel.classList.add('max-h-0', 'opacity-0', 'pointer-events-none');
                            toggle.setAttribute('aria-expanded', 'false');
                        } else {
                            panel.classList.remove('max-h-0', 'opacity-0', 'pointer-events-none');
                            panel.classList.add('max-h-96', 'opacity-100');
                            toggle.setAttribute('aria-expanded', 'true');
                        }

                        toggle.classList.toggle('bg-primary/10');
                    });
                }
            });
        </script>

        @if (session('scroll_to'))
            <script>
                window.addEventListener('load', function () {
                    var targetId = @json(session('scroll_to'));
                    var target = document.getElementById(targetId);
                    if (target) {
                        target.scrollIntoView({behavior: 'smooth', block: 'start'});
                        target.classList.add('ring', 'ring-primary/30');
                        setTimeout(function () {
                            target.classList.remove('ring', 'ring-primary/30');
                        }, 2000);
                    }
                });
            </script>
        @endif

        @stack('scripts')
    </body>
</html>
