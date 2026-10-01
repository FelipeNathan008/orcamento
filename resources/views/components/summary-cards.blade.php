@props(['cards' => []])

@php
$columns = min(count($cards), 4);

$styles = [
'default' => [
'border' => 'border-gray-200',
'background' => 'bg-gray-50',
'label' => 'text-gray-500',
'value' => 'text-gray-800',
'accent' => 'bg-gray-300',
'icon' => 'bg-gray-100 text-gray-500',
],
'primary' => [
'border' => 'border-orange-200',
'background' => 'bg-orange-50',
'label' => 'text-orange-700',
'value' => 'text-orange-700',
'accent' => 'bg-[#EA792D]',
'icon' => 'bg-orange-100 text-[#EA792D]',
],
'success' => [
'border' => 'border-green-200',
'background' => 'bg-green-50',
'label' => 'text-green-700',
'value' => 'text-green-700',
'accent' => 'bg-green-500',
'icon' => 'bg-green-100 text-green-600',
],
'warning' => [
'border' => 'border-yellow-200',
'background' => 'bg-yellow-50',
'label' => 'text-yellow-700',
'value' => 'text-yellow-700',
'accent' => 'bg-yellow-500',
'icon' => 'bg-yellow-100 text-yellow-600',
],
'danger' => [
'border' => 'border-red-200',
'background' => 'bg-red-50',
'label' => 'text-red-700',
'value' => 'text-red-700',
'accent' => 'bg-red-500',
'icon' => 'bg-red-100 text-red-600',
],
'info' => [
'border' => 'border-blue-200',
'background' => 'bg-blue-50',
'label' => 'text-blue-700',
'value' => 'text-blue-700',
'accent' => 'bg-blue-500',
'icon' => 'bg-blue-100 text-blue-600',
],
];

$gridColumns = [
1 => 'lg:grid-cols-1',
2 => 'lg:grid-cols-2',
3 => 'lg:grid-cols-3',
4 => 'lg:grid-cols-4',
][$columns] ?? 'lg:grid-cols-4';
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridColumns }} gap-4 mt-6">
    @foreach($cards as $card)
    @php
    $style = $styles[$card['type'] ?? 'default'] ?? $styles['default'];
    @endphp

    <div class="relative overflow-hidden rounded-xl border {{ $style['border'] }} {{ $style['background'] }} p-5 shadow-sm">
        <div class="absolute left-0 top-0 h-full w-1 {{ $style['accent'] }}"></div>

        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
                <p class="text-[10px] font-bold uppercase tracking-[0.14em] {{ $style['label'] }}">
                    {{ $card['title'] }}
                </p>

                <p class="mt-2 text-{{ $card['size'] ?? 'xl' }} font-bold leading-tight {{ $style['value'] }}">
                    {{ $card['value'] }}
                </p>

                @if(!empty($card['description']))
                <p class="mt-1 text-xs text-gray-500">
                    {{ $card['description'] }}
                </p>
                @endif
            </div>

            @if(!empty($card['icon']))
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $style['icon'] }}">
                {!! $card['icon'] !!}
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>