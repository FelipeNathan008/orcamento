@props([
'type' => 'gray',
'text' => null,
'dot' => true,
])

@php
$styles = [
'purple' => 'bg-purple-100 text-purple-700 border-purple-200',
'yellow' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
'blue' => 'bg-blue-100 text-blue-700 border-blue-200',
'pink' => 'bg-pink-100 text-pink-700 border-pink-200',
'green' => 'bg-green-100 text-green-700 border-green-200',
'red' => 'bg-red-100 text-red-700 border-red-200',
'orange' => 'bg-orange-100 text-orange-700 border-orange-200',
'emerald' => 'bg-emerald-100 text-emerald-900 border-emerald-400',
'gray' => 'bg-gray-100 text-gray-600 border-gray-200',
];

$style = $styles[$type] ?? $styles['gray'];
@endphp

<span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold {{ $style }}">
    @if($dot)
    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
    @endif
    {{ $text ?? $slot }}
</span>