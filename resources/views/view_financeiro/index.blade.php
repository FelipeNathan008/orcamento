@extends('layouts.app_financeiro')

@section('title', 'Financeiro')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Registros Financeiros
        </h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                HOME
            </a>
        </div>

    </div>

    <x-alert-flash />

    {{-- Formulário de Filtros --}}
    <form method="GET" action="{{ route('financeiro.index') }}" class="mb-6">

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- ID Financeiro --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        ID Financeiro
                    </label>

                    <input
                        type="number"
                        name="id_financeiro"
                        value="{{ request('id_financeiro') }}"
                        placeholder="Digite o ID..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                {{-- ID Orçamento --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        ID Orçamento
                    </label>

                    <input
                        type="number"
                        name="id_orcamento"
                        value="{{ request('id_orcamento') }}"
                        placeholder="Digite o ID..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                {{-- Cliente --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Cliente
                    </label>

                    <input
                        type="text"
                        name="cliente"
                        value="{{ request('cliente') }}"
                        placeholder="Digite o nome..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">

                        <option value="">Todos</option>

                        <option value="Aguardando pagamento"
                            {{ request('status') == 'Aguardando pagamento' ? 'selected' : '' }}>
                            Aguardando pagamento
                        </option>

                        <option value="Pagamento realizado"
                            {{ request('status') == 'Pagamento realizado' ? 'selected' : '' }}>
                            Pagamento realizado
                        </option>

                        <option value="Análise pedido"
                            {{ request('status') == 'Análise pedido' ? 'selected' : '' }}>
                            Análise pedido
                        </option>

                        <option value="Pedido fábrica"
                            {{ request('status') == 'Pedido fábrica' ? 'selected' : '' }}>
                            Pedido fábrica
                        </option>

                        <option value="Transportadora"
                            {{ request('status') == 'Transportadora' ? 'selected' : '' }}>
                            Transportadora
                        </option>

                        <option value="Entregue"
                            {{ request('status') == 'Entregue' ? 'selected' : '' }}>
                            Entregue
                        </option>

                    </select>
                </div>

                {{-- Buscar --}}
                <div class="flex items-end">
                    <button
                        type="submit"
                        class="w-full h-10 text-white rounded-md hover:opacity-90 transition"
                        style="background-color:#EA792D;">
                        Buscar
                    </button>
                </div>

                {{-- Limpar --}}
                <div class="flex items-end">
                    <a
                        href="{{ route('financeiro.index') }}"
                        class="w-full h-10 bg-gray-300 rounded-md text-gray-800 flex items-center justify-center hover:bg-gray-400 transition">
                        Limpar
                    </a>
                </div>

            </div>

        </div>

    </form>



    @if ($financeiro->isEmpty())

    @if(
    request('id_financeiro') ||
    request('id_orcamento') ||
    request('cliente') ||
    request('status')
    )

    <div class="text-center py-8">
        <p class="text-gray-600 text-lg">
            Nenhum registro financeiro encontrado com os filtros informados.
        </p>

        <a href="{{ route('financeiro.index') }}"
            class="inline-block mt-3 text-orange-600 hover:text-orange-700 font-medium">
            Limpar filtros
        </a>
    </div>

    @else

    <p class="text-gray-600 text-center py-8">
        Nenhum registro financeiro cadastrado ainda.
    </p>

    @endif

    @else
    {{-- TABELA --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        ID FINANCEIRO
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        ORÇAMENTO
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Cliente
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Valor Total
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200" id="financeiroTableBody">

                @foreach ($financeiro as $fin)

                @php
                $orcamento = $fin->orcamento;

                $fracionadoValido = true;
                $valorOriginal = 0;
                $valorFracionado = 0;
                $diferencaFracionado = 0;

                if ($orcamento && $orcamento->fracionados->isNotEmpty()) {

                $valorOriginal = (float) $orcamento->total_com_desconto;

                $valorFracionado = $orcamento->fracionados->sum(function ($fracionado) {
                return (float) $fracionado->total_com_desconto;
                });

                $diferencaFracionado = $valorOriginal - $valorFracionado;

                $fracionadoValido = abs($diferencaFracionado) < 0.01;
                    }
                    @endphp

                    <tr class="hover:bg-gray-50 transition duration-150"
                    data-status="{{ strtolower($fin->fin_status) }}">

                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $fin->id_fin }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $fin->orcamento_id_orcamento }}
                    </td>

                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $fin->fin_nome_cliente }}
                    </td>

                    <td class="px-4 py-4 text-sm text-right font-semibold text-gray-900 font-poppins whitespace-nowrap">
                        R$ {{ number_format($fin->fin_valor_total, 2, ',', '.') }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700 font-poppins">
                        @php
                        $normalizedStatus = strtolower(str_replace(' ', '_', $fin->fin_status));

                        switch ($normalizedStatus) {
                        case 'aguardando_pagamento':
                        $statusClass = 'bg-yellow-400';
                        break;

                        case 'pagamento_realizado':
                        $statusClass = 'bg-green-400';
                        break;

                        case 'análise_pedido':
                        case 'analise_pedido':
                        $statusClass = 'bg-blue-400';
                        break;

                        case 'pedido_fábrica':
                        case 'pedido_fabrica':
                        $statusClass = 'bg-purple-400';
                        break;

                        case 'transportadora':
                        $statusClass = 'bg-indigo-400';
                        break;

                        case 'entregue':
                        $statusClass = 'bg-green-600';
                        break;

                        default:
                        $statusClass = 'bg-indigo-400';
                        break;
                        }

                        // Quebra linha automática se tiver espaço
                        $formattedStatus = str_replace(' ', '<br>', ucfirst($fin->fin_status));
                        @endphp

                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight text-gray-900 text-center">
                            <span aria-hidden="true"
                                class="absolute inset-0 opacity-50 rounded-full {{ $statusClass }}"></span>
                            <span class="relative leading-tight">
                                {!! $formattedStatus !!}
                            </span>
                        </span>
                    </td>


                    <td class="px-6 py-4 text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-2">

                            {{-- Botão Status --}}
                            <button
                                type="button"
                                class="status-btn px-2 py-1 text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                                data-id="{{ $fin->id_fin }}">
                                Status
                            </button>

                            @php
                            $temFracionado = $orcamento && $orcamento->fracionados->isNotEmpty();

                            $statusPermitidosComFracionado = ['Pedido fábrica', 'Transportadora', 'Entregue', 'Análise pedido'];

                            $statusPermitidosSemFracionado = ['Análise pedido'];
                            @endphp

                            @if(
                            ($temFracionado && in_array($fin->fin_status, $statusPermitidosComFracionado))
                            ||
                            (!$temFracionado && in_array($fin->fin_status, $statusPermitidosSemFracionado))
                            )
                            <a href="{{ route('orcamento.fracionado.index', $fin->orcamento_id_orcamento) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-budget-bg hover:bg-button-budget-hover">
                                Fracionar
                            </a>
                            @endif

                            {{-- Forma Pagamento --}}
                            @if($fin->fin_status !== 'Entregue')
                            <a href="{{ route('forma_pagamento.index', ['id_fin' => $fin->id_fin]) }}"
                                class="px-2 py-1 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                Forma Pagamento
                            </a>
                            @endif

                            @php
                            // Verifica se tem algum log com log_situacao = 0
                            $temStatusPendente = \App\Models\LogStatus::where('log_id_orcamento', $fin->orcamento_id_orcamento)
                            ->where('log_situacao', 0)
                            ->exists();
                            @endphp

                            @php
                            $temFracionado = $orcamento && $orcamento->fracionados->isNotEmpty();
                            @endphp

                            @if(
                            !$temFracionado &&
                            $fin->fin_status !== 'Aguardando pagamento' &&
                            $fin->fin_status !== 'Entregue'
                            )

                            <form action="{{ route('financeiro.prosseguir', $fin->id_fin) }}"
                                method="POST"
                                class="form-prosseguir"
                                data-status="{{ $fin->fin_status }}">

                                @csrf

                                <button type="button"
                                    class="btn-prosseguir px-2 py-1 text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                                    data-id-fin="{{ $fin->id_fin }}"
                                    data-valor="{{ $fin->fin_valor_total }}"
                                    data-orcamento="{{ $fin->orcamento_id_orcamento }}">
                                    Prosseguir Status
                                </button>

                            </form>

                            @endif

                        </div>
                    </td>
                    </tr>

                    {{-- LINHA OCULTA STATUS --}}
                    <tr id="status-{{ $fin->id_fin }}" class="hidden">
                        <td colspan="6" class="px-6 py-4 text-sm text-gray-700">

                            @if($fin->logs->isEmpty())
                            <p class="text-center text-gray-500">Nenhum status encontrado.</p>
                            @else
                            <div class="grid grid-cols-6 gap-4">
                                @foreach ($fin->logs as $log)
                                @php
                                $status = $log->status_mercadoria_id_status;
                                $situacao = $log->log_situacao;
                                $img = $status . $situacao . '.png';
                                @endphp

                                <div class="flex flex-col items-center">
                                    <img src="/imagens_status/{{ $img }}" class="w-16 h-16 object-contain">
                                    <p class="text-sm text-gray-700 mt-2">
                                        {{$log->log_nome_status}}
                                    </p>
                                </div>
                                @endforeach
                            </div>
                            @endif

                        </td>
                    </tr>

                    @endforeach

            </tbody>
        </table>
    </div>
    <x-pagination-compact :paginator="$financeiro" />

    <x-modal-confirmacao
        id="modalConfirmarPedidoFabrica"
        titulo="Prosseguir para Pedido Fábrica"
        mensagem="Para prosseguir para Pedido Fábrica, é necessário confirmar com a fábrica que todos os produtos estão separados e disponíveis em estoque, garantindo que a compra possa ser efetivada."
        textoConfirmar="Sim, Prosseguir"
        textoCancelar="Cancelar" />

    <div id="modalAnalise" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center overflow-auto">

        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-3xl">

            <h2 class="text-xl font-bold mb-6">
                Lançar no Fluxo de Caixa
            </h2>

            <form id="formModalFluxo" action="{{ route('fluxo_caixa.storeFluxo') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-start gap-3">

                        <svg class="w-5 h-5 text-yellow-500 mt-0.5 flex-shrink-0"
                            fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.518 11.591c.75 1.334-.213 2.99-1.742 2.99H3.48c-1.53 0-2.492-1.656-1.743-2.99L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-1 1v3a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                                fill-rule="evenodd" />
                        </svg>

                        <div>
                            <p class="font-semibold text-yellow-800">
                                Atenção
                            </p>

                            <p class="text-sm text-yellow-700 mt-1">
                                O status do pedido só será atualizado após o lançamento do fluxo de caixa.
                            </p>
                        </div>

                    </div>

                    {{-- CARD INFORMATIVO --}}
                    <div class="md:col-span-2 bg-orange-50 border border-orange-200 rounded-lg p-6 shadow-sm">

                        <h2 class="text-lg font-bold text-orange-700 mb-4">
                            Informações do Lançamento
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">

                            <div>
                                <p class="text-gray-600">Orçamento</p>

                                <p id="orcamentoModal" class="font-semibold text-gray-900"></p>
                            </div>

                            <div>
                                <p class="text-gray-600">Tipo de Despesa</p>
                                <p class="font-semibold text-gray-900">Variável</p>
                            </div>

                            <div>
                                <p class="text-gray-600">Tipo</p>
                                <p class="font-semibold text-gray-900">Despesa UP</p>
                            </div>

                            <div>
                                <p class="text-gray-600">Movimentação</p>
                                <p class="font-semibold text-gray-900">Saída</p>
                            </div>

                        </div>

                        <input type="hidden" name="flu_tipo_despesa" value="Variavel">
                        <input type="hidden" name="flu_id_tipo" value="{{ $tipoDespesaUP->id_tipo_fluxo }}">
                        <input type="hidden" name="flu_id_movimentacao" value="{{ $movSaida->id_movimentacao }}">

                    </div>
                    {{-- DATA --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Data</label>
                        <input type="date"
                            name="flu_data_despesa"
                            id="modalData"
                            class="block w-full px-4 py-2 bg-white text-gray-800 rounded-md border border-gray-300"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Conta Bancária</label>

                        <select name="conta_bancaria_id"
                            class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                            required>

                            <option value="">Selecione</option>

                            @foreach ($contas as $conta)
                            <option value="{{ $conta->id_conta }}">
                                {{ $conta->conta_nome_banco }} - {{ $conta->numero_conta_corrente }}
                            </option>
                            @endforeach

                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Valor</label>
                        <input type="text" id="valorMask"
                            class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                            placeholder="R$ 0,00"
                            required>

                        {{-- valor real escondido --}}
                        <input type="hidden" name="flu_valor" id="valorReal">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- TIPO FISCAL --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">Tipo Fiscal</label>

                            <select name="flu_tipo_fiscal" id="tipoFiscalModal"
                                class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                                required>

                                <option value="">Selecione</option>
                                <option value="NF">Nota Fiscal (NF)</option>
                                <option value="RC">Recibo (RC)</option>
                                <option value="CF">Cupom Fiscal (CF)</option>
                                <option value="BO">Boleto (BO)</option>
                                <option value="CD">Cartão de Crédito (CD)</option>
                                <option value="OUT">Outros</option>
                            </select>
                        </div>

                        {{-- DOC --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">Num. Documento</label>

                            <input type="text" name="flu_num_doc" id="numDocModal"
                                class="w-full px-4 py-2 border rounded-md" placeholder="Opcional"
                                disabled>
                        </div>

                    </div>

                    {{-- DESCRIÇÃO --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Descrição</label>
                        <textarea name="flu_desc" placeholder="Pagamento do Orçamento #44"
                            class="block w-full px-4 py-2 bg-white text-gray-800 rounded-md border border-gray-300"
                            rows="3"
                            required></textarea>
                    </div>
                </div>

                <input type="hidden" name="id_financeiro" id="idFinanceiroModal">

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" id="cancelarModal"
                        class="px-4 py-2 bg-gray-300 rounded">
                        Cancelar
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded">
                        Salvar
                    </button>
                </div>

            </form>

        </div>
    </div>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        let formProsseguir = null;

        const modal = document.getElementById('modalAnalise');
        const btnCancelar = document.getElementById('cancelarModal');
        const formModal = document.getElementById('formModalFluxo');

        // Modal de confirmação exclusivo para "prosseguir status -> Pedido Fábrica"
        const modalConfirmarFabrica = document.getElementById('modalConfirmarPedidoFabrica');
        const btnConfirmarFabrica = modalConfirmarFabrica.querySelector('.btn-confirmar-confirmacao');
        const btnCancelarFabrica = modalConfirmarFabrica.querySelector('.btn-cancelar-confirmacao');

        // DATA AUTOMÁTICA
        document.getElementById('modalData').value =
            new Date().toISOString().split('T')[0];

        // ABRIR MODAL
        document.querySelectorAll('.btn-prosseguir').forEach(btn => {
            btn.addEventListener('click', function() {

                const form = btn.closest('form');
                const status = form.dataset.status.toLowerCase();

                if (status === 'análise pedido' || status === 'analise pedido') {

                    // guarda os dados do botão clicado para usar após a confirmação
                    formProsseguir = {
                        idFin: btn.dataset.idFin,
                        orcamento: btn.dataset.orcamento
                    };

                    modalConfirmarFabrica.classList.remove('hidden');
                    modalConfirmarFabrica.classList.add('flex');

                } else {
                    if (confirm('Tem certeza que deseja prosseguir o status?')) {
                        form.submit();
                    }
                }
            });
        });

        // CONFIRMOU avanço para Pedido Fábrica -> fecha a confirmação e abre o modal de fluxo de caixa
        btnConfirmarFabrica.addEventListener('click', function() {

            modalConfirmarFabrica.classList.add('hidden');
            modalConfirmarFabrica.classList.remove('flex');

            if (!formProsseguir) return;

            document.getElementById('idFinanceiroModal').value = formProsseguir.idFin;
            document.getElementById('orcamentoModal').textContent = formProsseguir.orcamento;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        // CANCELOU a confirmação
        btnCancelarFabrica.addEventListener('click', function() {
            modalConfirmarFabrica.classList.add('hidden');
            modalConfirmarFabrica.classList.remove('flex');
            formProsseguir = null;
        });

        const valorInput = document.getElementById('valorMask');
        const valorReal = document.getElementById('valorReal');
        const tipoFiscalModal = document.getElementById('tipoFiscalModal');
        const numDocModal = document.getElementById('numDocModal');

        tipoFiscalModal.addEventListener('change', function() {
            if (this.value) {
                numDocModal.disabled = false;
            } else {
                numDocModal.disabled = true;
                numDocModal.value = '';
            }
        });
        valorInput.addEventListener('input', function() {

            let value = this.value.replace(/\D/g, '');

            value = (value / 100).toFixed(2) + '';

            value = value.replace('.', ',');

            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            this.value = 'R$ ' + value;

            // salva sem máscara
            valorReal.value = value.replace(/\./g, '').replace(',', '.');
        });

        // REMOVE MÁSCARA AO ENVIAR
        document.getElementById('formModalFluxo').addEventListener('submit', function() {
            valorReal.value = valorInput.value
                .replace('R$ ', '')
                .replace(/\./g, '')
                .replace(',', '.');
        });


        // CANCELAR
        btnCancelar.addEventListener('click', function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });


        formModal.addEventListener('submit', function(e) {
            e.preventDefault();

            const valor = valorReal.value;

            const confirmar = confirm(
                `Tem certeza que deseja salvar o fluxo de caixa com o valor de R$ ${valor.replace('.', ',')}?`
            );

            if (!confirmar) return;

            this.submit();
        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.status-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = btn.dataset.id;

                document.querySelectorAll('[id^="status-"]').forEach(function(row) {
                    if (row.id !== 'status-' + id) {
                        row.classList.add('hidden');
                    }
                });

                const row = document.getElementById('status-' + id);
                if (row) row.classList.toggle('hidden');
            });
        });
    });


    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.btn-prosseguir-bloqueado').forEach(function(btn) {

            btn.addEventListener('click', function() {

                const diferenca = this.dataset.diferenca;
                const tipo = this.dataset.tipo;

                if (tipo === 'falta') {

                    alert(
                        'Não é possível prosseguir com o status.\n\n' +
                        'Ainda falta R$ ' + diferenca +
                        ' para que os valores dos orçamentos fracionados ' +
                        'correspondam ao orçamento original.'
                    );

                } else {

                    alert(
                        'Não é possível prosseguir com o status.\n\n' +
                        'Os orçamentos fracionados ultrapassaram o valor ' +
                        'original em R$ ' + diferenca + '.'
                    );
                }
            });

        });

    });
</script>
@endsection