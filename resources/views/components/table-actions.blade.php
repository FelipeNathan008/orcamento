@props([
'showRoute' => null,
'showText' => 'Ver',
'editRoute' => null,
'editText' => 'Editar',
'deleteAction' => null,
'deleteId' => null,
'deleteModal' => null,
'deleteText' => 'Excluir',
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
'statusButton' => false,
'statusId' => null,
'fracionarRoute' => null,
'fracionarText' => 'Fracionar',
'formaPagamentoRoute' => null,
'formaPagamentoText' => 'Forma Pagamento',
'prosseguirButton' => false,
'prosseguirId' => null,
'prosseguirValor' => null,
'prosseguirOrcamento' => null,
'statusAction' => null,
'statusText' => null,
'statusActive' => false,
'notificacaoRoute' => null,
'notificacaoText' => 'Cadastrar Notificação',
'subGrupoRoute' => null,
'subGrupoText' => 'Cadastrar Subgrupo',
])

<div class="inline-flex items-center justify-center gap-2 flex-wrap">
    @if ($subGrupoRoute)
    <a href="{{ $subGrupoRoute }}"
        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-orange-800 bg-orange-200 border border-orange-300 hover:bg-orange-300 hover:text-orange-900 transition">
        {{ $subGrupoText }}
    </a>
    @endif

    @if ($contactsRoute)
    <a href="{{ $contactsRoute }}"
        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-900 bg-blue-300 border border-blue-400 hover:bg-blue-400 hover:text-blue-950 transition">
        {{ $contactsText }}
    </a>
    @endif

    @if ($budgetsRoute)
    <a href="{{ $budgetsRoute }}"
        class="parcelas-btn relative inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-orange-900 bg-orange-300 border border-orange-400 hover:bg-orange-400 hover:text-orange-950 transition">
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
        {{ $editText ?? 'Editar' }}
    </a>
    @endif

    @if ($statusButton)
    <button type="button"
        data-id="{{ $statusId }}"
        class="status-btn inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-900 bg-blue-200 border border-blue-300 hover:bg-blue-300 hover:text-blue-950 transition">
        Status
    </button>
    @endif

    @if ($formaPagamentoRoute)
    <a href="{{ $formaPagamentoRoute }}"
        data-url="{{ $formaPagamentoRoute }}"
        class="dar-baixa-btn inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-green-800 bg-green-200 border border-green-300 hover:bg-green-300 hover:text-green-900 transition">
        {{ $formaPagamentoText }}
    </a>
    @endif

    @if ($prosseguirButton)
    <form action="{{ route('financeiro.prosseguir', $prosseguirId) }}" method="POST" class="form-prosseguir inline" data-status="{{ $slotStatus ?? '' }}">
        @csrf
        <input type="hidden" name="id_financeiro" value="{{ $prosseguirId }}">
        <input type="hidden" name="prosseguir_valor" value="{{ $prosseguirValor }}">
        <input type="hidden" name="prosseguir_orcamento" value="{{ $prosseguirOrcamento }}">
        <button type="button"
            data-id-fin="{{ $prosseguirId }}"
            data-valor="{{ $prosseguirValor }}"
            data-orcamento="{{ $prosseguirOrcamento }}"
            class="btn-prosseguir inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-purple-800 bg-purple-200 border border-purple-300 hover:bg-purple-300 hover:text-purple-900 transition">
            Prosseguir Status
        </button>
    </form>
    @endif

    @if ($fracionarRoute)
    <a href="{{ $fracionarRoute }}"
        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-orange-800 bg-orange-200 border border-orange-300 hover:bg-orange-300 hover:text-orange-900 transition">
        {{ $fracionarText }}
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

    @if($statusAction)
    @php
    $statusFormId = 'formStatus' . ($statusId ?? md5($statusAction));
    $statusModalId = 'modalStatus' . ($statusId ?? md5($statusAction));
    @endphp

    <form id="{{ $statusFormId }}Checkbox" action="{{ $statusAction }}" method="POST" class="inline-flex items-center">
        @csrf
        @method('PATCH')

        <button
            type="button"
            onclick="abrirModal('{{ $statusModalId }}', () => document.getElementById('{{ $statusFormId }}Checkbox').submit())"
            class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 hover:text-yellow-700 transition"
            title="{{ $statusActive ? 'Remover aguardando customização' : 'Marcar como aguardando customização' }}">
            <span class="w-4 h-4 rounded border flex items-center justify-center {{ $statusActive ? 'bg-yellow-500 border-yellow-500 text-white' : 'bg-white border-gray-300' }}">
                @if($statusActive)
                ✓
                @endif
            </span>
            Aguardar
        </button>
    </form>
    @endif

    @if ($notificacaoRoute)
    <a href="{{ $notificacaoRoute }}"
        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-red-800 bg-red-200 border border-red-300 hover:bg-red-300 hover:text-red-900 transition">
        {{ $notificacaoText }}
    </a>
    @endif


    @if ($deleteAction)
    <form id="{{ $deleteId }}" action="{{ $deleteAction }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="button"
            onclick="abrirModal('{{ $deleteModal }}', () => document.getElementById('{{ $deleteId }}').submit())"
            class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-red-800 bg-red-200 border border-red-300 hover:bg-red-300 hover:text-red-900 transition">
            {{ $deleteText }}
        </button>
    </form>
    @endif
</div>