@props(['title', 'route', 'label'])
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
    <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
        {{ $title }}
    </h1>
    <div class="flex items-center gap-3">
        <a href="{{ route('dashboard') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
            HOME
        </a>
        <a href="{{ $route }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 transition duration-150 ease-in-out"
            style="background-color: #EA792D;">
            {{ $label }}
        </a>
    </div>
</div>