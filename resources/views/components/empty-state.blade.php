@props([
'title' => 'Nenhum registro encontrado',
'message' => 'Não existem registros correspondentes aos filtros informados.',
'route' => null,
'routeParams' => [],
'buttonText' => 'Limpar filtros',
])

<div class="border border-dashed border-gray-300 rounded-xl py-14 px-6 text-center">
    <div class="flex items-center justify-center w-14 h-14 mx-auto mb-4 rounded-full bg-gray-100 text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none"
            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z" />
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M14 3v5h5" />
        </svg>
    </div>

    <h3 class="text-base font-bold text-gray-700">
        {{ $title }}
    </h3>

    <p class="text-sm text-gray-500 mt-2">
        {{ $message }}
    </p>

    @if ($route)
    <a href="{{ route($route, $routeParams) }}"
        class="inline-flex items-center gap-2 mt-5 px-4 py-2 rounded-lg text-sm font-semibold text-white hover:brightness-95 transition"
        style="background-color:#EA792D;">
        {{ $buttonText }}
    </a>
    @endif
</div>