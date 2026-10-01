@props([
'paginator',
])

@if ($paginator->hasPages()) <div class="mt-5 flex justify-center"> <div class="inline-flex items-center gap-1.5 p-1.5 bg-gray-50 border border-gray-200 rounded-xl shadow-sm">
        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <span class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed">
                ‹
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
                class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-600 hover:bg-orange-100 hover:text-orange-700 transition font-semibold">
                ‹
            </a>
        @endif

        @php
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();

            $pages = [];

            if ($last <= 7) {
                for ($i = 1; $i <= $last; $i++) {
                    $pages[] = $i;
                }
            } else {
                $pages[] = 1;

                if ($current > 4) {
                    $pages[] = '...';
                }

                $start = max(2, $current - 1);
                $end = min($last - 1, $current + 1);

                for ($i = $start; $i <= $end; $i++) {
                    $pages[] = $i;
                }

                if ($current < $last - 3) {
                    $pages[] = '...';
                }

                $pages[] = $last;
            }

            $pages = array_values(array_unique($pages));
        @endphp

        @foreach ($pages as $page)
            @if ($page === '...')
                <span class="w-9 h-9 flex items-center justify-center text-gray-400 text-sm font-semibold">
                    ...
                </span>
            @elseif ($page == $current)
                <span
                    class="w-9 h-9 flex items-center justify-center rounded-lg text-white text-sm font-bold shadow-sm"
                    style="background-color:#EA792D;">
                    {{ $page }}
                </span>
            @else
                <a href="{{ $paginator->url($page) }}"
                    class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-600 text-sm font-semibold hover:bg-orange-100 hover:text-orange-700 transition">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        {{-- Próxima --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
                class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-600 hover:bg-orange-100 hover:text-orange-700 transition font-semibold">
                ›
            </a>
        @else
            <span class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed">
                ›
            </span>
        @endif

    </div>
</div>

@endif
