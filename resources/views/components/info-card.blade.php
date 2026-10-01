@props([
    'title' => 'Informações',
    'name' => null,
    'type' => null,
    'fields' => [],
])

@php
$fieldCount = count($fields);

$gridColumns = match(true) {
    $fieldCount <= 1 => 'lg:grid-cols-1',
    $fieldCount === 2 => 'lg:grid-cols-2',
    $fieldCount === 3 => 'lg:grid-cols-3',
    $fieldCount === 4 => 'lg:grid-cols-4',
    $fieldCount === 5 => 'lg:grid-cols-5',
    default => 'lg:grid-cols-4',
};
@endphp

<div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 sm:px-7">
        <div class="flex items-center justify-between gap-4">
            <div class="flex min-w-0 items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-orange-100 text-[#EA792D]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                </div>

                <div class="min-w-0">
                    <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#EA792D]">
                        {{ $title }}
                    </p>

                    @if($name)
                    <h2 class="mt-1 truncate text-lg font-bold text-gray-800 sm:text-xl">
                        {{ $name }}
                    </h2>
                    @endif
                </div>
            </div>

            @if($type)
            <span class="shrink-0 rounded-md border border-orange-200 bg-orange-50 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wide text-[#EA792D]">
                {{ $type }}
            </span>
            @endif
        </div>
    </div>

    @if($fieldCount)
    <div class="grid grid-cols-1 sm:grid-cols-2 {{ $gridColumns }}">
        @foreach($fields as $field)
        <div class="min-w-0 border-b border-gray-100 px-6 py-4 last:border-b-0 sm:border-b-0 sm:border-r sm:border-gray-100 sm:last:border-r-0 lg:border-b-0">
            <p class="mb-1.5 text-[10px] font-bold uppercase tracking-[0.12em] text-gray-400">
                {{ $field['label'] }}
            </p>

            <p class="text-sm leading-5 {{ ($field['bold'] ?? false) ? 'font-semibold text-gray-800' : 'text-gray-600' }} {{ ($field['break'] ?? false) ? 'break-all' : 'break-words' }}">
                {{ $field['value'] ?? 'Não informado' }}
            </p>
        </div>
        @endforeach
    </div>
    @endif
</div>