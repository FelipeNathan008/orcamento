@extends('layouts.app_financeiro')

@section('title', 'Financeiro')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Registros Financeiros">
            <x-header-action href="{{ route('dashboard') }}" color="gray">
                Home
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('financeiro.index') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="id_financeiro" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                ID Financeiro
                            </label>
                            <input type="number" id="id_financeiro" name="id_financeiro" value="{{ request('id_financeiro') }}"
                                placeholder="ID financeiro..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="id_orcamento" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                ID Orçamento
                            </label>
                            <input type="number" id="id_orcamento" name="id_orcamento" value="{{ request('id_orcamento') }}"
                                placeholder="ID orçamento..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="cliente" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Cliente
                            </label>
                            <input type="text" id="cliente" name="cliente" value="{{ request('cliente') }}"
                                placeholder="Nome do cliente..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="status" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Status
                            </label>
                            <select id="status" name="status"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todos</option>
                                <option value="Aguardando pagamento" {{ request('status') == 'Aguardando pagamento' ? 'selected' : '' }}>Aguardando pagamento</option>
                                <option value="Pagamento realizado" {{ request('status') == 'Pagamento realizado' ? 'selected' : '' }}>Pagamento realizado</option>
                                <option value="Análise pedido" {{ request('status') == 'Análise pedido' ? 'selected' : '' }}>Análise pedido</option>
                                <option value="Pedido fábrica" {{ request('status') == 'Pedido fábrica' ? 'selected' : '' }}>Pedido fábrica</option>
                                <option value="Transportadora" {{ request('status') == 'Transportadora' ? 'selected' : '' }}>Transportadora</option>
                                <option value="Entregue" {{ request('status') == 'Entregue' ? 'selected' : '' }}>Entregue</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <x-primary-button class="w-full h-11">
                                <x-icons.search />
                                Buscar
                            </x-primary-button>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('financeiro.index') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 hover:text-gray-800 transition">
                            <x-icons.reset />
                            Limpar filtros
                        </a>
                    </div>
                </x-filter-card>
            </form>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Lista de registros financeiros</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">{{ $financeiro->total() }} registro(s)</span>
                </div>
            </div>

            @if ($financeiro->isEmpty())
            @if (request('id_financeiro') || request('id_orcamento') || request('cliente') || request('status'))
            <x-empty-state
                title="Nenhum registro financeiro encontrado"
                message="Não existem registros financeiros correspondentes aos filtros informados."
                route="financeiro.index"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhum registro financeiro cadastrado"
                message="Ainda não existem registros financeiros cadastrados no sistema."
                route="financeiro.index"
                button-text="Voltar ao financeiro" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Financeiro</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Orçamento</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Cliente</th>
                                <th class="px-5 py-4 text-right text-[11px] font-bold text-white uppercase tracking-wider">Valor Total</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Status</th>
                                <th class="px-2 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100" id="financeiroTableBody">
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

                                $statusTipo=match ($fin->fin_status) {
                                'Aguardando pagamento' => 'yellow',
                                'Pagamento realizado' => 'green',
                                'Análise pedido' => 'blue',
                                'Pedido fábrica' => 'purple',
                                'Transportadora' => 'blue',
                                'Entregue' => 'emerald',
                                default => 'gray',
                                };

                                $temFracionado = $orcamento && $orcamento->fracionados->isNotEmpty();
                                $statusPermitidosComFracionado = ['Pedido fábrica', 'Transportadora', 'Entregue', 'Análise pedido'];
                                $statusPermitidosSemFracionado = ['Análise pedido'];
                                @endphp

                                <tr class="group hover:bg-orange-50/40 transition" data-status="{{ strtolower($fin->fin_status) }}">
                                    <td class="px-5 py-5 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                                <x-icons.document />
                                            </div>
                                            <span class="text-sm font-bold text-gray-800">#{{ $fin->id_fin }}</span>
                                        </div>
                                    </td>

                                    <td class="px-5 py-5 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-gray-700">
                                            #{{ $fin->orcamento_id_orcamento }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-5 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ $fin->fin_nome_cliente ?: 'N/D' }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-5 text-right whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-800">
                                            R$ {{ number_format($fin->fin_valor_total, 2, ',', '.') }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-5 whitespace-nowrap">
                                        <x-badge :type="$statusTipo" :text="$fin->fin_status" />
                                    </td>

                                    <td class="px-2 py-5 text-center whitespace-nowrap">
                                        <x-table-actions
                                            :status-button="true"
                                            :status-id="$fin->id_fin"
                                            :forma-pagamento-route="$fin->fin_status !== 'Entregue' ? route('forma_pagamento.index', CryptHelper::encrypt($fin->id_fin)) : null"
                                            :fracionar-route="(
                                                ($temFracionado && in_array($fin->fin_status, $statusPermitidosComFracionado)) ||
                                                (!$temFracionado && in_array($fin->fin_status, $statusPermitidosSemFracionado))
                                            ) ? route('orcamento.fracionado.index', CryptHelper::encrypt($fin->orcamento_id_orcamento)) : null"
                                            :prosseguir-button="(
                                                !$temFracionado &&
                                                $fin->fin_status !== 'Aguardando pagamento' &&
                                                $fin->fin_status !== 'Entregue'
                                            )"
                                            :prosseguir-id="CryptHelper::encrypt($fin->id_fin)"
                                            :prosseguir-valor="$fin->fin_valor_total"
                                            :prosseguir-orcamento="$fin->orcamento_id_orcamento" />
                                    </td>


                                </tr>

                                <tr id="status-{{ $fin->id_fin }}" class="hidden">
                                    <td colspan="6" class="px-5 py-5 bg-gray-50">
                                        @if ($fin->logs->isEmpty())
                                        <p class="text-center text-sm text-gray-500">
                                            Nenhum status encontrado.
                                        </p>
                                        @else
                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                                            @foreach ($fin->logs as $log)
                                            @php
                                            $status = $log->status_mercadoria_id_status;
                                            $situacao = $log->log_situacao;
                                            $img = $status . $situacao . '.png';
                                            @endphp
                                            <div class="flex flex-col items-center text-center">
                                                <img src="/imagens_status/{{ $img }}" class="w-16 h-16 object-contain">
                                                <p class="text-xs text-gray-700 mt-2">
                                                    {{ $log->log_nome_status }}
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
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$financeiro" />
            </div>

            <x-modal-confirmacao
                id="modalConfirmarPedidoFabrica"
                titulo="Prosseguir para Pedido Fábrica"
                mensagem="Para prosseguir para Pedido Fábrica, é necessário confirmar com a fábrica que todos os produtos estão separados e disponíveis em estoque, garantindo que a compra possa ser efetivada."
                textoConfirmar="Sim, Prosseguir"
                textoCancelar="Cancelar" />

            <x-modal-confirmacao
                id="modalConfirmarProsseguir"
                titulo="Prosseguir Status"
                mensagem="Deseja realmente prosseguir para o próximo status deste pedido?"
                textoConfirmar="Sim, Prosseguir"
                textoCancelar="Cancelar" />

            <div id="modalAnalise" class="fixed inset-0 z-50 bg-black/50 hidden items-center justify-center overflow-auto px-4 py-8">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl overflow-hidden">
                    <div class="px-6 sm:px-8 py-5 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">
                            Lançar no Fluxo de Caixa
                        </h2>
                    </div>

                    <form id="formModalFluxo" action="{{ route('fluxo_caixa.storeFluxo') }}" method="POST" class="p-6 sm:p-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2 flex items-start gap-3 px-4 py-3 bg-yellow-50 border border-yellow-100 rounded-lg">
                                <svg class="w-5 h-5 text-yellow-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.518 11.591c.75 1.334-.213 2.99-1.742 2.99H3.48c-1.53 0-2.492-1.656-1.743-2.99L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-1 1v3a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="font-semibold text-yellow-800">Atenção</p>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        O status do pedido só será atualizado após o lançamento do fluxo de caixa.
                                    </p>
                                </div>
                            </div>

                            <div class="md:col-span-2 bg-orange-50 border border-orange-100 rounded-xl p-5">
                                <h2 class="text-lg font-bold text-orange-700 mb-4">
                                    Informações do Lançamento
                                </h2>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Orçamento</p>
                                        <p id="orcamentoModal" class="font-semibold text-gray-900 mt-1"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Tipo de Despesa</p>
                                        <p class="font-semibold text-gray-900 mt-1">Variável</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Tipo</p>
                                        <p class="font-semibold text-gray-900 mt-1">Despesa UP</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Movimentação</p>
                                        <p class="font-semibold text-gray-900 mt-1">Saída</p>
                                    </div>
                                </div>

                                <input type="hidden" name="flu_tipo_despesa" value="Variavel">
                                <input type="hidden" name="flu_id_tipo" value="{{ $tipoDespesaUP->id_tipo_fluxo }}">
                                <input type="hidden" name="flu_id_movimentacao" value="{{ $movSaida->id_movimentacao }}">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                    Data
                                </label>
                                <input type="date" name="flu_data_despesa" id="modalData"
                                    class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                    required>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                    Conta Bancária
                                </label>
                                <select name="conta_bancaria_id"
                                    class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
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
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                    Valor
                                </label>
                                <input type="text" id="valorMask"
                                    class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                    placeholder="R$ 0,00"
                                    required>
                                <input type="hidden" name="flu_valor" id="valorReal">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                    Tipo Fiscal
                                </label>
                                <select name="flu_tipo_fiscal" id="tipoFiscalModal"
                                    class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
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

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                    Num. Documento
                                </label>
                                <input type="text" name="flu_num_doc" id="numDocModal"
                                    class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                    placeholder="Opcional"
                                    disabled>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                    Descrição
                                </label>
                                <textarea name="flu_desc"
                                    placeholder="Pagamento do Orçamento #44"
                                    class="block w-full px-3 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                                    rows="3"
                                    required></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="id_financeiro" id="idFinanceiroModal">

                        <div class="flex justify-end gap-2 mt-6 pt-5 border-t border-gray-200">
                            <button type="button" id="cancelarModal"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let formProsseguir = null;
        const modal = document.getElementById('modalAnalise');
        const btnCancelar = document.getElementById('cancelarModal');
        const formModal = document.getElementById('formModalFluxo');
        const modalData = document.getElementById('modalData');

        if (modalData) {
            modalData.value = new Date().toISOString().split('T')[0];
        }

        document.querySelectorAll('.btn-prosseguir').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('.form-prosseguir');
                const status = this.closest('tr')?.dataset.status?.toLowerCase() || '';

                if (!form) return;

                formProsseguir = {
                    form,
                    idFin: this.dataset.idFin,
                    orcamento: this.dataset.orcamento
                };

                if (status === 'análise pedido' || status === 'analise pedido') {
                    abrirModal('modalConfirmarPedidoFabrica', function() {
                        fecharModal('modalConfirmarPedidoFabrica');

                        document.getElementById('idFinanceiroModal').value = formProsseguir.idFin;
                        document.getElementById('orcamentoModal').textContent = formProsseguir.orcamento;

                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    });
                } else {
                    abrirModal('modalConfirmarProsseguir', function() {
                        fecharModal('modalConfirmarProsseguir');
                        formProsseguir.form.submit();
                    });
                }
            });
        });

        const valorInput = document.getElementById('valorMask');
        const valorReal = document.getElementById('valorReal');
        const tipoFiscalModal = document.getElementById('tipoFiscalModal');
        const numDocModal = document.getElementById('numDocModal');

        if (tipoFiscalModal) {
            tipoFiscalModal.addEventListener('change', function() {
                numDocModal.disabled = !this.value;
                if (!this.value) numDocModal.value = '';
            });
        }

        if (valorInput) {
            valorInput.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');

                if (!value) {
                    this.value = '';
                    valorReal.value = '';
                    return;
                }

                value = (parseInt(value) / 100).toFixed(2).replace('.', ',');
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                this.value = 'R$ ' + value;
                valorReal.value = value.replace(/\./g, '').replace(',', '.');
            });
        }

        if (formModal) {
            formModal.addEventListener('submit', function(e) {
                e.preventDefault();

                valorReal.value = valorInput.value
                    .replace('R$ ', '')
                    .replace(/\./g, '')
                    .replace(',', '.');

                abrirModal('modalConfirmarFluxo', function() {
                    fecharModal('modalConfirmarFluxo');
                    formModal.submit();
                });
            });
        }

        if (btnCancelar) {
            btnCancelar.addEventListener('click', function() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        }

        document.querySelectorAll('.status-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = btn.dataset.id;

                document.querySelectorAll('[id^="status-"]').forEach(function(row) {
                    if (row.id !== 'status-' + id) {
                        row.classList.add('hidden');
                    }
                });

                const row = document.getElementById('status-' + id);

                if (row) {
                    row.classList.toggle('hidden');
                }
            });
        });
    });
</script>
@endpush
@endsection