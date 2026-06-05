@if ($paginator->hasPages())
    <nav class="flex items-center justify-center gap-1">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1.5 text-sm text-slate-300 border border-slate-200 rounded-lg cursor-not-allowed">&lsaquo;</span>
        @else
            <button wire:click="previousPage" class="px-3 py-1.5 text-sm text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-100 transition-colors">&lsaquo;</button>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-1.5 text-sm text-slate-400">...</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1.5 text-sm font-semibold text-white bg-purple-600 border border-purple-600 rounded-lg">{{ $page }}</span>
                    @else
                        <button wire:click="gotoPage({{ $page }})" class="px-3 py-1.5 text-sm text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-100 transition-colors">{{ $page }}</button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" class="px-3 py-1.5 text-sm text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-100 transition-colors">&rsaquo;</button>
        @else
            <span class="px-3 py-1.5 text-sm text-slate-300 border border-slate-200 rounded-lg cursor-not-allowed">&rsaquo;</span>
        @endif
    </nav>
@endif