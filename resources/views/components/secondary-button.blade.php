@props([
    'href' => null,
    'type' => 'button',
])

@if ($href)
    <a href="{{ $href }}"
        {{ $attributes->merge([
            'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 hover:bg-gray-200 transition'
        ]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}"
        {{ $attributes->merge([
            'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 hover:bg-gray-200 transition'
        ]) }}>
        {{ $slot }}
    </button>
@endif