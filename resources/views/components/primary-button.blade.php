@props([
    'type' => 'submit',
])

<button type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center gap-2 rounded-lg text-sm font-semibold text-white shadow-sm hover:shadow-md hover:brightness-95 transition'
    ]) }}
    style="background-color:#EA792D;">
    {{ $slot }}
</button>