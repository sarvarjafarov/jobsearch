@extends('layout', ['title' => 'Jobify Blog'])
@php use Illuminate\Support\Str; @endphp

@section('hero')
    <div class="bg-gradient-to-r from-primary to-[#7F70FF]">
        <div class="mx-auto max-w-6xl px-6 py-16 text-white">
            <p class="text-xs uppercase tracking-[0.3em] text-white/70">Insights</p>
            <h1 class="mt-3 text-4xl font-semibold leading-tight">Jobify Blog</h1>
            <p class="mt-4 max-w-2xl text-lg text-white/80">Hiring wisdom, candidate playbooks, and product updates from the Jobify team.</p>
        </div>
    </div>
@endsection

@section('content')
    <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5">
        <form action="{{ route('blog.index') }}" class="flex flex-col gap-3 md:flex-row md:items-center">
            <input
                type="text"
                name="q"
                value="{{ $query }}"
                placeholder="Search articles"
                class="w-full rounded-2xl border border-slate-200 px-4 py-3 shadow-sm focus:border-primary focus:ring-primary md:flex-1"
            >
            <button class="rounded-2xl bg-primary px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:opacity-90">
                Search
            </button>
        </form>
    </section>

    <section class="space-y-6">
        <div class="grid gap-6 md:grid-cols-3">
            @forelse ($posts as $post)
                <article class="flex h-full flex-col rounded-3xl bg-white shadow-sm ring-1 ring-black/5">
                    @if($post->cover_image)
                        <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="h-48 w-full rounded-t-3xl object-cover">
                    @endif
                    <div class="flex flex-1 flex-col p-6">
                        <p class="text-xs uppercase tracking-wide text-primary">{{ optional($post->published_at)->format('M d, Y') }}</p>
                        <h2 class="mt-2 text-xl font-semibold text-body">{{ $post->title }}</h2>
                        <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-600">{{ $post->excerpt ?? Str::limit(strip_tags($post->body), 160) }}</p>
                        <a href="{{ route('blog.show', $post) }}" class="mt-4 text-sm font-semibold text-primary hover:underline">Read more →</a>
                    </div>
                </article>
            @empty
                <p class="rounded-3xl bg-white p-8 text-center text-slate-500">No posts yet. Check back soon!</p>
            @endforelse
        </div>

        {{ $posts->links() }}
    </section>
@endsection
