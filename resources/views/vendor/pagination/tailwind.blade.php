@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col items-center gap-4 text-sm text-slate-600">
        <div class="flex w-full items-center justify-between gap-3 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-center font-medium text-slate-400">{!! __('pagination.previous') !!}</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-center font-semibold text-primary transition hover:border-primary/40">{!! __('pagination.previous') !!}</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-center font-semibold text-primary transition hover:border-primary/40">{!! __('pagination.next') !!}</a>
            @else
                <span class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-center font-medium text-slate-400">{!! __('pagination.next') !!}</span>
            @endif
        </div>

        <div class="hidden w-full items-center justify-between sm:flex">
            <div class="text-xs uppercase tracking-[0.2em] text-slate-400">
                {{ __('Showing') }}
                <span class="font-semibold text-slate-900">{{ $paginator->firstItem() }}</span>
                {{ __('to') }}
                <span class="font-semibold text-slate-900">{{ $paginator->lastItem() }}</span>
                {{ __('of') }}
                <span class="font-semibold text-slate-900">{{ $paginator->total() }}</span>
                {{ __('results') }}
            </div>
            <div class="inline-flex rounded-2xl border border-slate-200 bg-white shadow-sm">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-4 py-2 text-slate-400">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="px-4 py-2 font-semibold text-primary">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 text-slate-500 hover:text-primary">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
    </nav>
@endif
