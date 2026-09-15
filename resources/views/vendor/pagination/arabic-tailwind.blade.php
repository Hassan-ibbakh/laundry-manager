@if ($paginator->hasPages())
    <nav role="navigation" aria-label="التنقل بين الصفحات" class="flex flex-wrap items-center justify-between gap-3" dir="rtl">
        <p class="text-xs font-bold text-slate-500">
            عرض {{ $paginator->firstItem() ?? 0 }} إلى {{ $paginator->lastItem() ?? 0 }} من {{ $paginator->total() }}
        </p>

        <div class="flex items-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-9 items-center rounded-lg border border-slate-200 px-3 text-xs font-bold text-slate-300" aria-disabled="true">
                    السابق
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-9 items-center rounded-lg border border-slate-200 px-3 text-xs font-bold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                    السابق
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-9 items-center px-2 text-xs font-bold text-slate-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg bg-blue-600 px-3 text-xs font-black text-white shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-slate-200 px-3 text-xs font-bold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-9 items-center rounded-lg border border-slate-200 px-3 text-xs font-bold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                    التالي
                </a>
            @else
                <span class="inline-flex h-9 items-center rounded-lg border border-slate-200 px-3 text-xs font-bold text-slate-300" aria-disabled="true">
                    التالي
                </span>
            @endif
        </div>
    </nav>
@endif
