@extends('layouts.app_financeiro')

@section('title', 'Formas de Pagamento')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Formas de Pagamento
        </h1>

        <div class="flex items-center gap-3">

            <a href="{{ route('financeiro.index') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400">
                VOLTAR
            </a>

            <a href="{{ route('forma_pagamento.create', ['financeiro_id' => $id]) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white"
                style="background-color: #EA792D;">
                Nova Forma
            </a>

        </div>

    </div>

    <x-alert-flash />

    @if($id && isset($financeiroSelecionado))
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <h2 class="text-lg font-bold text-orange-700 mb-3">
            Informações do Financeiro Selecionado
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

            <div>
                <p class="text-gray-600">ID do Financeiro</p>
                <p class="font-semibold text-gray-900">
                    {{ $financeiroSelecionado->id_fin }}
                </p>
            </div>

            <div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-gray-600">ID Orcamento</p>
                        <p class="font-semibold">
                            {{ $financeiroSelecionado->orcamento->id_orcamento }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600">Cód. Interno</p>
                        <p class="font-semibold">
                            {{ $financeiroSelecionado->orcamento->orc_cod_interno ?: 'N/D' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600">Cód. Fábrica</p>
                        <p class="font-semibold">
                            {{ $financeiroSelecionado->orcamento->orc_cod_fabrica ?: 'N/D' }}
                        </p>
                    </div>
                </div>
            </div>

            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold text-gray-900">
                    {{ $financeiroSelecionado->fin_nome_cliente }}
                </p>
            </div>

        </div>

    </div>
    <div class="mb-6 p-4 bg-gray-100 rounded-lg flex justify-around text-center">
        <div>
            <span class="font-bold text-lg text-yellow-600">Valor Total</span>
            <span class="block text-gray-900 text-lg">R$ {{ number_format($valorTotal, 2, ',', '.') }}</span>
        </div>
        <div>
            <span class="font-bold text-lg text-green-600">Valor Pago</span>
            <span class="block text-gray-900 text-lg">R$ {{ number_format($valorPago, 2, ',', '.') }}</span>
        </div>
        <div>
            <span class="font-bold text-lg text-blue-600">Valor Entrada</span>
            <span class="block text-gray-900 text-lg">R$ {{ number_format($entrada, 2, ',', '.') }}</span>
        </div>
        <div>
            <span class="font-bold text-lg text-blue-600">Valor Negociado</span>
            <span class="block text-gray-900 text-lg">R$ {{ number_format($valorNegociado, 2, ',', '.') }}</span>
        </div>
        <div>
            <span class="font-bold text-lg text-red-600">Saldo Devedor</span>
            <span class="block text-gray-900 text-lg">R$ {{ number_format($valorFaltante, 2, ',', '.') }}</span>
        </div>
    </div>
    @endif
    {{-- SEM PAGAMENTOS --}}
    @if ($formasPagamento->isEmpty())

    <p class="text-gray-600 text-center py-8">
        Nenhuma forma de pagamento cadastrada.
    </p>

    @else

    {{-- TABELA --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">

        <table class="min-w-full divide-y divide-gray-200">

            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Tipo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Conta Bancária</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Prazo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Data</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Valor</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase">Ações</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">

                @foreach ($formasPagamento as $forma)

                <tr>

                    <td class="px-4 py-4 text-sm">
                        {{ $forma->id_forma_pag }}
                    </td>

                    <td class="px-4 py-4 text-sm">
                        {{ $forma->tipoPagamento?->tipo_plano_fin }}
                    </td>

                    <td class="px-4 py-4 text-sm">
                        @if($forma->conta)
                        {{ $forma->conta->conta_nome_banco }}
                        @else
                        N/A
                        @endif
                    </td>

                    <td class="px-4 py-4 text-sm">
                        {{ $forma->forma_prazo }}
                    </td>

                    <td class="px-4 py-4 text-sm">
                        {{ $forma->forma_data ? \Carbon\Carbon::parse($forma->forma_data)->format('d/m/Y') : 'N/D' }}
                    </td>

                    <td class="px-4 py-4 text-sm">
                        R$ {{ number_format($forma->forma_valor,2,',','.') }}
                    </td>


                    <td class="px-2 py-4 text-center">
                        <div class="flex justify-center items-center gap-2 flex-wrap">
                            @if($forma->forma_prazo === 'Parcelado' || ($forma->forma_prazo === 'Entrada' && $forma->forma_qtd_parcela > 1))
                            <button
                                class="parcelas-btn px-2 py-1 text-xs font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600"
                                data-id="{{ $forma->id_forma_pag }}">
                                Parcelas
                            </button>
                            @endif

                            <a href="{{ route('forma_pagamento.show', $forma->id_forma_pag) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Ver
                            </a>

                        </div>
                    </td>
                </tr>
                {{-- PARCELAS --}}
                <tr id="parcelas-{{ $forma->id_forma_pag }}" class="hidden">

                    <td colspan="9" class="bg-blue-50 p-4">
                        <h3 class="text-lg font-bold text-blue-800 mb-3">
                            Parcelas ({{ $forma->forma_qtd_parcela }})
                        </h3>
                        @if($forma->detalhes->isEmpty())

                        <p class="text-gray-600 text-sm">
                            Nenhuma parcela cadastrada.
                        </p>
                        @else
                        <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-md">

                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-xs font-bold uppercase">ID</th>
                                    <th class="px-4 py-2 text-xs font-bold uppercase">Valor</th>
                                    <th class="px-4 py-2 text-xs font-bold uppercase">Vencimento</th>
                                    <th class="px-4 py-2 text-xs font-bold uppercase">Situação</th>
                                    <th class="px-4 py-2 text-xs font-bold uppercase">Pagamento</th>
                                    <th class="px-4 py-2 text-xs font-bold uppercase">Ações</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($forma->detalhes as $parcela)

                                <tr>
                                    <td class="px-4 py-2 text-sm">
                                        #{{ $parcela->id_det_forma }}
                                    </td>

                                    <td class="px-4 py-2 text-sm">
                                        R$ {{ number_format($parcela->det_forma_valor_parcela,2,',','.') }}
                                    </td>

                                    <td class="px-4 py-2 text-sm {{ $parcela->classe_vencimento }}">
                                        {{ \Carbon\Carbon::parse($parcela->det_forma_data_venc)->format('d/m/Y') }}
                                    </td>

                                    <td class="px-4 py-2 text-sm">
                                        <span class="px-2 py-1 rounded-md text-xs font-semibold {{ $parcela->cor_status }}">
                                            {{ $parcela->status_exibicao }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-2 text-sm">
                                        {{ $parcela->det_forma_data_pagamento ? \Carbon\Carbon::parse($parcela->det_forma_data_pagamento)->format('d/m/Y') 
                                    : 'N/D' }}
                                    </td>

                                    <td class="px-4 py-2 text-sm text-center">
                                        <div class="flex justify-center items-center gap-2 flex-wrap">

                                            {{-- DAR BAIXA --}}
                                            @if(in_array($parcela->det_situacao, ['Não Pago','Acordo','Inadimplencia','Atrasado']))
                                            <form method="POST">
                                                @csrf
                                                <button
                                                    type="button"
                                                    data-id="{{ $parcela->id_det_forma }}"
                                                    onclick="abrirModalPagamento(this)"
                                                    class="px-2 py-1 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                                    Dar Baixa
                                                </button>
                                            </form>
                                            @endif

                                            {{-- VOLTAR --}}
                                            @if($parcela->det_situacao === 'Pago')
                                            <form id="formVoltarNaoPago{{ $parcela->id_det_forma }}"
                                                action="{{ route('parcelas.voltarNaoPago', $parcela->id_det_forma) }}"
                                                method="POST">
                                                @csrf

                                                <button
                                                    type="button"
                                                    onclick="abrirModal('modalVoltarNaoPago', () => document.getElementById('formVoltarNaoPago{{ $parcela->id_det_forma }}').submit())"
                                                    class="px-2 py-1 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                                    Voltar Para Não Pago
                                                </button>
                                            </form>
                                            @endif

                                        </div>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
<!-- MODAL -->
<div id="modalPagamento" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">

        <h2 class="text-lg font-bold mb-3">Data do Pagamento</h2>

        <div class="bg-yellow-50 border-2 border-yellow-400 rounded-md p-3 mb-4">
            <p class="text-sm font-bold text-yellow-800">
                ⚠️ ATENÇÃO: confira a data do pagamento.
            </p>
            <p class="text-sm text-yellow-700 mt-1">
                A data selecionada será registrada no sistema como a data em que esta parcela foi paga.
            </p>
        </div>
        <form id="formBaixa" method="POST">
            @csrf


            <div class="mb-4">
                <label class="block text-sm text-gray-600">Selecione a data:</label>
                <input type="date" name="data_pagamento" id="data_pagamento" required class="w-full border rounded p-2">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="fecharModalPagamento()" class="bg-gray-400 text-white px-3 py-1 rounded">
                    Cancelar
                </button>

                <button type="button" id="btnConfirmarBaixa" onclick="confirmarData()" class="bg-green-600 text-white px-3 py-1 rounded">
                    Confirmar
                </button>
            </div>

        </form>
    </div>
</div>

<div id="modalConfirmarBaixa"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        <h2 class="text-xl font-bold mb-4">
            Confirmar baixa
        </h2>
        <p class="text-gray-700 mb-6">
            A data que será registrada no sistema é:
            <span id="dataConfirmacaoBaixa" class="font-bold text-green-600"></span>.
            <br><br>
            Após confirmar, a parcela será marcada como paga.
            <br><br>
            Deseja realmente confirmar esta data?
        </p>

        <div class="flex justify-end gap-3">
            <button
                type="button"
                id="btnConfirmarBaixaFinal"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Confirmar
            </button>
            <button
                type="button"
                id="btnCancelarConfirmacaoBaixa"
                class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-400">
                Cancelar
            </button>
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalVoltarNaoPago"
    titulo="Voltar para não pago"
    mensagem="Tem certeza que deseja voltar esta parcela para Não Pago?"
    textoConfirmar="Confirmar" />

<script>
    let parcelaId = null;
    let enviandoBaixa = false;

    function abrirModalPagamento(element) {
        parcelaId = element.dataset.id;
        enviandoBaixa = false;

        const btn = document.getElementById('btnConfirmarBaixa');

        btn.disabled = false;
        btn.innerText = 'Confirmar';
        btn.classList.remove('opacity-70', 'cursor-not-allowed');

        document.getElementById('data_pagamento').value = '';
        document.getElementById('modalPagamento').classList.remove('hidden');
        document.getElementById('modalPagamento').classList.add('flex');
    }

    function fecharModalPagamento() {
        if (enviandoBaixa) {
            return;
        }

        document.getElementById('modalPagamento').classList.add('hidden');
        document.getElementById('modalPagamento').classList.remove('flex');
    }

    function confirmarData() {
        if (enviandoBaixa) {
            return;
        }

        const data = document.getElementById('data_pagamento').value;

        if (!data) {
            alert('Selecione uma data!');
            return;
        }

        const partes = data.split('-');
        const dataFormatada = partes[2] + '/' + partes[1] + '/' + partes[0];

        document.getElementById('dataConfirmacaoBaixa').innerText = dataFormatada;

        const modal = document.getElementById('modalConfirmarBaixa');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    document.getElementById('btnCancelarConfirmacaoBaixa').addEventListener('click', function() {
        if (enviandoBaixa) {
            return;
        }

        const modal = document.getElementById('modalConfirmarBaixa');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    document.getElementById('btnConfirmarBaixaFinal').addEventListener('click', function() {
        if (enviandoBaixa || this.disabled) {
            return;
        }

        enviandoBaixa = true;

        this.disabled = true;
        this.innerText = 'Salvando...';
        this.classList.add('opacity-70', 'cursor-not-allowed');

        const btnBaixa = document.getElementById('btnConfirmarBaixa');

        btnBaixa.disabled = true;
        btnBaixa.innerText = 'SALVANDO...';
        btnBaixa.classList.add('opacity-70', 'cursor-not-allowed');

        const data = document.getElementById('data_pagamento').value;
        const form = document.getElementById('formBaixa');

        form.action = `/parcelas/${parcelaId}/dar-baixa`;
        form.querySelector('#data_pagamento').value = data;

        form.submit();
    });

    document.getElementById('modalConfirmarBaixa').addEventListener('click', function(e) {
        if (e.target === this && !enviandoBaixa) {
            this.classList.add('hidden');
            this.classList.remove('flex');
        }
    });
</script>
{{-- SCRIPT --}}
<script>
    document.querySelectorAll('.parcelas-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id
            document.querySelectorAll('[id^="parcelas-"]').forEach(row => {
                if (row.id !== 'parcelas-' + id) {
                    row.classList.add('hidden')
                }
            })
            const row = document.getElementById('parcelas-' + id)
            if (row) {
                row.classList.toggle('hidden')
            }
        })
    })
</script>
@endsection