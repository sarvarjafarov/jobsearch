<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Jobify' }}</title>
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
            <nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
                <a href="{{ route('home') }}" class="text-xl font-semibold text-primary tracking-tight">Jobify</a>
                <div class="flex items-center gap-6 text-sm font-medium">
                    <a href="{{ route('home') }}" class="transition hover:text-primary">Home</a>
                    <a href="{{ route('jobs.create') }}" class="transition hover:text-primary">Post Job</a>
                    <a href="#contact" class="transition hover:text-primary">Contact</a>
                </div>
            </nav>
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

        <section id="contact" class="bg-white py-16">
            <div class="mx-auto max-w-4xl rounded-2xl bg-gradient-to-r from-primary to-[#7F70FF] p-[1px] shadow-md">
                <div class="rounded-[1.1rem] bg-white px-8 py-10 text-center">
                    <p class="text-xs uppercase tracking-[0.25em] text-primary/70">Contact</p>
                    <h2 class="mt-2 text-2xl font-semibold text-body">Let’s build hiring that feels effortless.</h2>
                    <p class="mt-3 text-base text-slate-600">Need help curating roles or customizing Jobify for your team? Email <a class="font-medium text-primary" href="mailto:hello@jobify.com">hello@jobify.com</a>.</p>
                </div>
            </div>
        </section>

        <footer class="bg-background py-6 text-center text-sm text-slate-500">
            Built with Laravel & Tailwind CSS.
        </footer>

        @stack('scripts')
    </body>
</html>
