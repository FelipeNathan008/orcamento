@extends('layouts.app_financeiro')

@section('title', 'Formas de Pagamento')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Formas de Pagamento" :back-url="route('financeiro.index')">
            <x-header-action href="{{ route('forma_pagamento.create', CryptHelper::encrypt($id)) }}">
                Nova Forma
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        @if($id && isset($financeiroSelecionado))
        <div class="px-6 sm:px-8 pt-4">
            <x-info-card
                title="Financeiro selecionado"
                :name="$financeiroSelecionado->fin_nome_cliente"
                type="Cliente"
                :fields="[
                    [
                        'label' => 'ID Financeiro',
                        'value' => $financeiroSelecionado->id_fin,
                        'bold' => true,
                    ],
                    [
                        'label' => 'ID Orçamento',
                        'value' => $financeiroSelecionado->orcamento->id_orcamento ?? 'N/D',
                    ],
                    [
                        'label' => 'Código interno',
                        'value' => $financeiroSelecionado->orcamento->orc_cod_interno ?: 'Não informado',
                    ],
                    [
                        'label' => 'Código da fábrica',
                        'value' => $financeiroSelecionado->orcamento->orc_cod_fabrica ?: 'Não informado',
                    ],
                ]" />
        </div>

        <div class="px-6 sm:px-8 pt-5 pb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4">
                    <p class="text-xs font-bold text-yellow-700 uppercase tracking-wide">Valor Total</p>
                    <p class="mt-1 text-lg font-bold text-gray-800">
                        R$ {{ number_format($valorTotal, 2, ',', '.') }}
                    </p>
                </div>

                <div class="bg-green-50 border border-green-100 rounded-xl p-4">
                    <p class="text-xs font-bold text-green-700 uppercase tracking-wide">Valor Pago</p>
                    <p class="mt-1 text-lg font-bold text-gray-800">
                        R$ {{ number_format($valorPago, 2, ',', '.') }}
                    </p>
                </div>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <p class="text-xs font-bold text-blue-700 uppercase tracking-wide">Valor Entrada</p>
                    <p class="mt-1 text-lg font-bold text-gray-800">
                        R$ {{ number_format($entrada, 2, ',', '.') }}
                    </p>
                </div>

                <div class="bg-purple-50 border border-purple-100 rounded-xl p-4">
                    <p class="text-xs font-bold text-purple-700 uppercase tracking-wide">Valor Negociado</p>
                    <p class="mt-1 text-lg font-bold text-gray-800">
                        R$ {{ number_format($valorNegociado, 2, ',', '.') }}
                    </p>
                </div>

                <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                    <p class="text-xs font-bold text-red-700 uppercase tracking-wide">Saldo Devedor</p>
                    <p class="mt-1 text-lg font-bold text-gray-800">
                        R$ {{ number_format($valorFaltante, 2, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Formas cadastradas</h2>

                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">
                        {{ $formasPagamento->count() }} forma(s)
                    </span>
                </div>
            </div>

            @if($formasPagamento->isEmpty())
            <x-empty-state
                title="Nenhuma forma de pagamento cadastrada"
                message="Ainda não existem formas de pagamento cadastradas para este financeiro."
                route="forma_pagamento.create"
                :route-params="['financeiro_id' => CryptHelper::encrypt($id)]"
                button-text="Cadastrar forma de pagamento" />
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Pagamento</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Conta Bancária</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Prazo</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Data</th>
                                <th class="px-5 py-4 text-right text-[11px] font-bold text-white uppercase tracking-wider">Valor</th>
                                <th class="px-2 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($formasPagamento as $forma)
                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h2m2 0h2m-8 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">
                                                #{{ $forma->id_forma_pag }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $forma->tipoPagamento?->tipo_plano_fin ?? 'N/D' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $forma->conta?->conta_nome_banco ?? 'Não informado' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $forma->forma_prazo }}
                                    </span>

                                    @if($forma->forma_qtd_parcela > 1)
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $forma->forma_qtd_parcela }} parcela(s)
                                    </p>
                                    @endif
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">
                                        {{ $forma->forma_data ? \Carbon\Carbon::parse($forma->forma_data)->format('d/m/Y') : 'N/D' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 text-right whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-800">
                                        R$ {{ number_format($forma->forma_valor, 2, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-2 py-5 text-center whitespace-nowrap">
                                    <div class="flex justify-center items-center gap-2 flex-wrap">
                                        @if($forma->forma_prazo === 'Parcelado' || ($forma->forma_prazo === 'Entrada' && $forma->forma_qtd_parcela > 1))
                                        <x-table-actions
                                            :budgets-route="'#parcelas-'.$forma->id_forma_pag"
                                            budgets-text="Parcelas"
                                            :budgets-count="null" />
                                        @endif

                                        <x-table-actions
                                            :show-route="route('forma_pagamento.show', CryptHelper::encrypt($forma->id_forma_pag))"
                                            show-text="Ver" />
                                    </div>
                                </td>
                            </tr>

                            <tr id="parcelas-{{ $forma->id_forma_pag }}" class="hidden">
                                <td colspan="6" class="px-5 py-5 bg-gray-50 border-t border-gray-200">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <h3 class="text-sm font-bold text-gray-800">Parcelas</h3>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $forma->forma_qtd_parcela }} parcela(s) vinculada(s) a esta forma de pagamento.
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-orange-50 border border-orange-100 text-xs font-semibold text-orange-700">
                                            {{ $forma->forma_qtd_parcela }} parcela(s)
                                        </span>
                                    </div>

                                    @if($forma->detalhes->isEmpty())
                                    <div class="border border-gray-200 rounded-xl bg-white p-5 text-center">
                                        <p class="text-sm text-gray-500">
                                            Nenhuma parcela cadastrada.
                                        </p>
                                    </div>
                                    @else
                                    <div class="border border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-600 uppercase tracking-wider">Parcela</th>
                                                        <th class="px-4 py-3 text-right text-[10px] font-bold text-gray-600 uppercase tracking-wider">Valor</th>
                                                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-600 uppercase tracking-wider">Vencimento</th>
                                                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-600 uppercase tracking-wider">Situação</th>
                                                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-600 uppercase tracking-wider">Pagamento</th>
                                                        <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-600 uppercase tracking-wider">Ações</th>
                                                    </tr>
                                                </thead>

                                                <tbody class="divide-y divide-gray-100">
                                                    @foreach($forma->detalhes as $parcela)
                                                    <tr class="hover:bg-gray-50 transition">
                                                        <td class="px-4 py-4 whitespace-nowrap">
                                                            <span class="text-sm font-bold text-gray-700">
                                                                #{{ $parcela->id_det_forma }}
                                                            </span>
                                                        </td>

                                                        <td class="px-4 py-4 text-right whitespace-nowrap">
                                                            <span class="text-sm font-semibold text-gray-800">
                                                                R$ {{ number_format($parcela->det_forma_valor_parcela, 2, ',', '.') }}
                                                            </span>
                                                        </td>

                                                        <td class="px-4 py-4 whitespace-nowrap">
                                                            <span class="text-sm {{ $parcela->classe_vencimento }}">
                                                                {{ \Carbon\Carbon::parse($parcela->det_forma_data_venc)->format('d/m/Y') }}
                                                            </span>
                                                        </td>

                                                        <td class="px-4 py-4 whitespace-nowrap">
                                                            @php
                                                            $tipoStatus = match ($parcela->det_situacao) {
                                                            'Não Pago' => 'yellow',
                                                            'Pago' => 'green',
                                                            'Quitado' => 'emerald',
                                                            'Acordo' => 'blue',
                                                            'Inadimplencia' => 'red',
                                                            'Atrasado' => 'red',
                                                            default => 'gray',
                                                            };
                                                            @endphp

                                                            <x-badge
                                                                :type="$tipoStatus"
                                                                :text="$parcela->det_situacao" />
                                                        </td>

                                                        <td class="px-4 py-4 whitespace-nowrap">
                                                            <span class="text-sm text-gray-700">
                                                                {{ $parcela->det_forma_data_pagamento ? \Carbon\Carbon::parse($parcela->det_forma_data_pagamento)->format('d/m/Y') : 'N/D' }}
                                                            </span>
                                                        </td>

                                                        <td class="px-4 py-4 text-center whitespace-nowrap">
                                                            <div class="flex justify-center items-center gap-2 flex-wrap">
                                                                @if(in_array($parcela->det_situacao, ['Não Pago', 'Acordo', 'Inadimplencia', 'Atrasado']))
                                                                <x-table-actions
                                                                    :forma-pagamento-route="route('parcelas.darBaixa', CryptHelper::encrypt($parcela->id_det_forma))"
                                                                    forma-pagamento-text="Dar Baixa" />
                                                                @endif

                                                                @if($parcela->det_situacao === 'Pago')
                                                                <x-table-actions
                                                                    :delete-action="route('parcelas.voltarNaoPago', CryptHelper::encrypt($parcela->id_det_forma))"
                                                                    delete-id="formVoltarNaoPago{{ $parcela->id_det_forma }}"
                                                                    delete-modal="modalVoltarNaoPago"
                                                                    delete-text="Voltar para não pago" />
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div id="modalPagamento" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Data do pagamento</h2>
        </div>

        <div class="p-6">
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-5">
                <p class="text-sm font-bold text-yellow-800">Atenção: confira a data do pagamento.</p>
                <p class="text-sm text-yellow-700 mt-1">
                    A data selecionada será registrada no sistema como a data em que esta parcela foi paga.
                </p>
            </div>

            <form id="formBaixa" method="POST">
                @csrf
                <div class="mb-5">
                    <label for="data_pagamento" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                        Data do pagamento
                    </label>
                    <input
                        type="date"
                        name="data_pagamento"
                        id="data_pagamento"
                        required
                        class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                </div>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        onclick="fecharModalPagamento()"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                        Cancelar
                    </button>

                    <button
                        type="button"
                        id="btnConfirmarBaixa"
                        onclick="confirmarData()"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalConfirmarBaixa" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 px-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Confirmar baixa</h2>
        </div>

        <div class="p-6">
            <p class="text-sm text-gray-700 leading-relaxed">
                A data que será registrada no sistema é:
                <span id="dataConfirmacaoBaixa" class="font-bold text-green-600"></span>.
            </p>

            <p class="text-sm text-gray-700 mt-4 leading-relaxed">
                Após confirmar, a parcela será marcada como paga.
            </p>

            <p class="text-sm font-semibold text-gray-800 mt-4">
                Deseja realmente confirmar esta data?
            </p>

            <div class="flex justify-end gap-3 mt-6">
                <button
                    type="button"
                    id="btnCancelarConfirmacaoBaixa"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                    Cancelar
                </button>

                <button
                    type="button"
                    id="btnConfirmarBaixaFinal"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalVoltarNaoPago"
    titulo="Voltar para não pago"
    mensagem="Tem certeza que deseja voltar esta parcela para Não Pago?"
    textoConfirmar="Confirmar" />

@push('scripts')
<script>
    let parcelaId = null;
    let parcelaUrl = null;
    let enviandoBaixa = false;

    function abrirModalPagamento(element) {
        parcelaId = element.dataset.id;
        parcelaUrl = element.dataset.url;
        enviandoBaixa = false;

        const btn = document.getElementById('btnConfirmarBaixa');
        btn.disabled = false;
        btn.innerText = 'Confirmar';
        btn.classList.remove('opacity-70', 'cursor-not-allowed');

        document.getElementById('data_pagamento').value = '';

        const modal = document.getElementById('modalPagamento');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function fecharModalPagamento() {
        if (enviandoBaixa) return;

        const modal = document.getElementById('modalPagamento');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function confirmarData() {
        if (enviandoBaixa) return;

        const data = document.getElementById('data_pagamento').value;
        if (!data) {
            alert('Selecione uma data!');
            return;
        }

        const partes = data.split('-');
        document.getElementById('dataConfirmacaoBaixa').innerText =
            `${partes[2]}/${partes[1]}/${partes[0]}`;

        const modal = document.getElementById('modalConfirmarBaixa');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    document.getElementById('btnCancelarConfirmacaoBaixa').addEventListener('click', function() {
        if (enviandoBaixa) return;

        const modal = document.getElementById('modalConfirmarBaixa');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    document.getElementById('btnConfirmarBaixaFinal').addEventListener('click', function() {
        if (enviandoBaixa || this.disabled) return;

        enviandoBaixa = true;
        this.disabled = true;
        this.innerText = 'Salvando...';
        this.classList.add('opacity-70', 'cursor-not-allowed');

        const btnBaixa = document.getElementById('btnConfirmarBaixa');
        btnBaixa.disabled = true;
        btnBaixa.innerText = 'Salvando...';
        btnBaixa.classList.add('opacity-70', 'cursor-not-allowed');

        const form = document.getElementById('formBaixa');
        form.action = parcelaUrl;
        form.querySelector('#data_pagamento').value = document.getElementById('data_pagamento').value;
        form.submit();
    });

    document.getElementById('modalConfirmarBaixa').addEventListener('click', function(e) {
        if (e.target === this && !enviandoBaixa) {
            this.classList.add('hidden');
            this.classList.remove('flex');
        }
    });

    document.querySelectorAll('.parcelas-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            const id = this.getAttribute('href').replace('#parcelas-', '');

            document.querySelectorAll('[id^="parcelas-"]').forEach(row => {
                if (row.id !== 'parcelas-' + id) {
                    row.classList.add('hidden');
                }
            });

            const row = document.getElementById('parcelas-' + id);
            if (row) row.classList.toggle('hidden');
        });
    });

    document.querySelectorAll('.dar-baixa-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            abrirModalPagamento(this);
        });
    });
</script>
@endpush
@endsection