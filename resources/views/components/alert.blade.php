@props([
    'type' => 'success',
    'message' => ''
])

@php
$config = [
    'success' => [
        'bg' => 'bg-green-100',
        'border' => 'border-green-400',
        'text' => 'text-green-700',
        'title' => 'Sucesso!'
    ],
    'error' => [
        'bg' => 'bg-red-100',
        'border' => 'border-red-400',
        'text' => 'text-red-700',
        'title' => 'Erro!'
    ],
    'warning' => [
        'bg' => 'bg-yellow-100',
        'border' => 'border-yellow-400',
        'text' => 'text-yellow-700',
        'title' => 'Aviso!'
    ],
    'info' => [
        'bg' => 'bg-blue-100',
        'border' => 'border-blue-400',
        'text' => 'text-blue-700',
        'title' => 'Informação'
    ]
];

$style = $config[$type];
@endphp

<div class="{{ $style['bg'] }} border {{ $style['border'] }} {{ $style['text'] }} px-4 py-3 rounded-md mb-4">
    <strong class="font-bold">{{ $style['title'] }}</strong>
    <span class="block sm:inline">{{ $message }}</span>
</div>