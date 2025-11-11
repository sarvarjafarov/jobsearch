@extends('layout', ['title' => $post->title .' — Jobify Blog'])
@php use Illuminate\Support\Str; @endphp

@section('hero')
    <div class="bg-slate-900">
        <div class="mx-auto max-w-4xl px-6 py-16 text-white">
            <p class="text-xs uppercase tracking-[0.3em] text-white/60">Jobify Blog</p>
            <h1 class="mt-3 text-4xl font-semibold leading-tight">{{ $post->title }}</h1>
            <div class="mt-4 flex flex-wrap gap-4 text-sm text-white/70">
                <span>{{ optional($post->published_at)->format('F d, Y') }}</span>
                @if($post->author_name)
                    <span>By {{ $post->author_name }}</span>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('content')
    <article class="mx-auto max-w-4xl space-y-6">
        @if($post->cover_image)
            <img src="{{ $post->cover_image }}" alt="{{ $post->title }}" class="w-full rounded-3xl object-cover shadow-sm">
        @endif

        <div class="prose prose-slate max-w-none text-lg leading-relaxed text-slate-700">
            {!! nl2br(e($post->body)) !!}
        </div>
    </article>

    @if($morePosts->isNotEmpty())
        <section class="mt-12 space-y-4">
            <h2 class="text-2xl font-semibold text-body">More from Jobify</h2>
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($morePosts as $related)
                    <a href="{{ route('blog.show', $related) }}" class="rounded-2xl border border-slate-100 p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-primary/40 hover:shadow-md">
                        <p class="text-xs uppercase tracking-wide text-primary">{{ optional($related->published_at)->format('M d, Y') }}</p>
                        <h3 class="mt-2 text-lg font-semibold text-body">{{ $related->title }}</h3>
                        <p class="mt-3 text-sm text-slate-500">{{ $related->excerpt ?? Str::limit(strip_tags($related->body), 120) }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endsection
