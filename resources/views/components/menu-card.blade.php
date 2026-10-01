@props([
    'href',
    'title',
    'description',
    'icon' => 'document',
])

<a href="{{ $href }}"
    class="group bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-xl hover:border-orange-200 transition-all duration-200">

    <div class="flex items-start gap-4">

        <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-orange-100 text-orange-600 shrink-0 group-hover:bg-orange-500 group-hover:text-white transition">
            @if ($icon === 'building')
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h2m-2 4h2m-2 4h2m4-8h2m-2 4h2m-2 4h2" />
                </svg>
            @elseif ($icon === 'users')
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zm13 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14 3v5h5" />
                </svg>
            @endif
        </div>

        <div class="min-w-0">
            <h2 class="text-lg font-bold text-gray-800 group-hover:text-orange-600 transition">
                {{ $title }}
            </h2>

            <p class="text-sm text-gray-500 mt-1 leading-relaxed">
                {{ $description }}
            </p>
        </div>

        <div class="ml-auto text-gray-300 group-hover:text-orange-500 transition">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </div>

    </div>
</a>