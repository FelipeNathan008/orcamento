  @if ($paginator->hasPages())
<div class="mt-8 flex justify-center">
    <div class="flex items-center gap-2">

        {{-- Anterior --}}
        @if (!$paginator->onFirstPage())
            <a href="{{ $paginator->previousPageUrl() }}"
                class="w-10 h-10 flex items-center justify-center rounded-md bg-gray-100 text-gray-700 hover:text-white transition"
                style="--hover-bg: #EA792D;"
                onmouseover="this.style.backgroundColor='#EA792D'"
                onmouseout="this.style.backgroundColor=''">
                ‹
            </a>
        @endif

        @php
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();

            $pages = [];

            for ($i = 1; $i <= min(3, $last); $i++) {
                $pages[] = $i;
            }

            if ($current > 4 && $current < $last - 3) {
                $pages[] = '...';
                $pages[] = $current;
            }

            if ($last > 6) {
                $pages[] = '...';
                $pages[] = $last - 1;
                $pages[] = $last;
            }

            $pages = array_unique($pages);
        @endphp

        @foreach($pages as $page)

            @if($page === '...')
                <span class="w-10 h-10 flex items-center justify-center text-gray-500 font-bold">
                    ...
                </span>

            @elseif($page == $current)
                <span
                    class="w-10 h-10 flex items-center justify-center rounded-md text-white font-bold shadow"
                    style="background-color:#EA792D;">
                    {{ $page }}
                </span>

            @else
                <a href="{{ $paginator->url($page) }}"
                    class="w-10 h-10 flex items-center justify-center rounded-md bg-gray-100 text-gray-700 font-semibold hover:text-white transition"
                    onmouseover="this.style.backgroundColor='#EA792D'"
                    onmouseout="this.style.backgroundColor=''">
                    {{ $page }}
                </a>
            @endif

        @endforeach

        {{-- Próxima --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
                class="w-10 h-10 flex items-center justify-center rounded-md bg-gray-100 text-gray-700 hover:text-white transition"
                onmouseover="this.style.backgroundColor='#EA792D'"
                onmouseout="this.style.backgroundColor=''">
                ›
            </a>
        @endif

    </div>
</div>
@endif