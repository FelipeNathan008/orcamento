@props([
'showRoute' => null,
'showText' => 'Ver',
'editRoute' => null,
'deleteAction' => null,
'deleteId' => null,
'deleteModal' => null,
'contactsRoute' => null,
'contactsText' => 'Contatos',
'budgetsRoute' => null,
'budgetsText' => 'Orçamentos',
'budgetsCount' => null,
'layoutRoute' => null,
'layoutText' => 'Layout',
'saldoRoute' => null,
'saldoText' => 'Saldo',
'saldoId' => null,
'saldoConta' => null,
])

<div class="inline-flex items-center justify-center gap-2">
    @if ($contactsRoute)
    <a href="{{ $contactsRoute }}"
        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-900 bg-blue-300 border border-blue-400 hover:bg-blue-400 hover:text-blue-950 transition">
        {{ $contactsText }}
    </a>
    @endif

    @if ($budgetsRoute)
    <a href="{{ $budgetsRoute }}"
        class="relative inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-orange-900 bg-orange-300 border border-orange-400 hover:bg-orange-400 hover:text-orange-950 transition">
        {{ $budgetsText }}
        @if ($budgetsCount > 0)
        <span class="absolute -right-2 -top-2 inline-flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
            {{ $budgetsCount }}
        </span>
        @endif
    </a>
    @endif

    @if ($layoutRoute)
    <a href="{{ $layoutRoute }}"
        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-purple-800 bg-purple-200 border border-purple-300 hover:bg-purple-300 hover:text-purple-900 transition">
        {{ $layoutText }}
    </a>
    @endif

    @if ($showRoute)
    <a href="{{ $showRoute }}"
        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-800 bg-blue-200 border border-blue-300 hover:bg-blue-300 hover:text-blue-900 transition">
        {{ $showText }}
    </a>
    @endif

    @if ($editRoute)
    <a href="{{ $editRoute }}"
        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-orange-800 bg-orange-200 border border-orange-300 hover:bg-orange-300 hover:text-orange-900 transition">
        Editar
    </a>
    @endif

    @if ($saldoRoute)
    <button type="button"
        data-id="{{ $saldoId }}"
        data-conta="{{ $saldoConta }}"
        data-saldo-route="{{ $saldoRoute }}"
        class="btnAbrirSaldo inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-green-800 bg-green-200 border border-green-300 hover:bg-green-300 hover:text-green-900 transition">
        {{ $saldoText }}
    </button>
    @endif

    @if ($deleteAction)
    <form id="{{ $deleteId }}" action="{{ $deleteAction }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="button"
            onclick="abrirModal('{{ $deleteModal }}', () => document.getElementById('{{ $deleteId }}').submit())"
            class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-red-800 bg-red-200 border border-red-300 hover:bg-red-300 hover:text-red-900 transition">
            Excluir
        </button>
    </form>
    @endif
</div>