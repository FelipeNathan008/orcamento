@props([
'href',
'color' => 'orange',
])

@php
$classes = match ($color) {
'gray' => 'text-gray-700 bg-gray-100 border-gray-200 hover:bg-gray-200',
'blue' => 'text-white border-transparent bg-blue-600 hover:bg-blue-700',
'orange' => 'text-white border-transparent hover:brightness-95',
default => 'text-white border-transparent hover:brightness-95',
};

$style = $color === 'orange'
? 'background-color:#EA792D;'
: null;
@endphp

<a href="{{ $href }}"
    {{ $attributes->merge([
'class' => "inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm hover:shadow-md transition {$classes}"
]) }}
    @if ($style) style="{{ $style }}" @endif>
    {{ $slot }} </a>