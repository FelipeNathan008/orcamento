@props([
    'status',
])

@php
    $statusClass = match($status) {
        'Não Pago' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
        'Atrasado', 'Inadimplencia' => 'bg-red-100 text-red-700 border-red-200',
        'Pago', 'Quitado' => 'bg-green-100 text-green-700 border-green-200',
        'Acordo' => 'bg-blue-100 text-blue-700 border-blue-200',
        default => 'bg-gray-100 text-gray-700 border-gray-200',
    };

    $statusIcon = match($status) {
        'Atrasado', 'Inadimplencia' => '!',
        default => '●',
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full border text-[11px] font-bold {{ $statusClass }}">
    <span class="flex items-center justify-center w-4 h-4 rounded-full bg-current/10 text-[10px]">
        {{ $statusIcon }}
    </span>

    {{ $status }}
</span>