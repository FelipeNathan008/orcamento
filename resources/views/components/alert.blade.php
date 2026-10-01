@props([
'type' => 'success',
'message' => ''
])

@php
$config = [
'success' => [
'bg' => 'bg-green-50',
'border' => 'border-green-200',
'text' => 'text-green-700',
'icon' => 'text-green-600',
'title' => 'Sucesso!'
],
'error' => [
'bg' => 'bg-red-50',
'border' => 'border-red-200',
'text' => 'text-red-700',
'icon' => 'text-red-600',
'title' => 'Erro!'
],
'warning' => [
'bg' => 'bg-yellow-50',
'border' => 'border-yellow-200',
'text' => 'text-yellow-700',
'icon' => 'text-yellow-600',
'title' => 'Aviso!'
],
'info' => [
'bg' => 'bg-blue-50',
'border' => 'border-blue-200',
'text' => 'text-blue-700',
'icon' => 'text-blue-600',
'title' => 'Informação'
]
];

$style = $config[$type];
@endphp

<div class="flex items-start gap-3 {{ $style['bg'] }} border {{ $style['border'] }} {{ $style['text'] }} px-4 py-3.5 rounded-xl mb-4">

    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-white/70 {{ $style['icon'] }} shrink-0">
        @if ($type === 'success')
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        @elseif ($type === 'error')
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
        @elseif ($type === 'warning')
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.7l-8 14A2 2 0 004 21h16a2 2 0 001.7-3.3l-8-14a2 2 0 00-3.4 0z" />
        </svg>
        @else
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="9" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0-9h.01" />
        </svg>
        @endif
    </div>

    <div class="min-w-0">
        <strong class="block text-sm font-bold">{{ $style['title'] }}</strong>
        <span class="block text-sm mt-0.5">{{ $message }}</span>
    </div>

</div>