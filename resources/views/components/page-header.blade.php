@props([
'title',
'backRoute' => null,
'backRouteParams' => [],
'backUrl' => null,
'backText' => 'Voltar',
])

<div class="px-6 sm:px-8 py-6 border-b border-gray-100">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-custom-dark-text font-bai-jamjuree">
                {{ $title }}
            </h1>
        </div>
        <div class="flex items-center gap-3">
            @php
            $hrefVoltar = $backUrl ?? ($backRoute ? route($backRoute, $backRouteParams) : null);
            @endphp
            @if ($hrefVoltar)
            <x-secondary-button :href="$hrefVoltar">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                {{ $backText }}
            </x-secondary-button>
            @endif
            {{ $slot }}
        </div>
    </div>
</div>
