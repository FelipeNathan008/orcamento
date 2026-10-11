@extends('layouts.app_financeiro')

@section('title', 'Orçamento Fracionado')

@php
use App\Helpers\CryptHelper;

$valorBrutoOriginal = (float) $orcamento->total_bruto;
$valorOriginal = (float) $orcamento->total_com_desconto;
$valorFracionado = $orcamento->fracionados->sum(fn($fracionado) => (float) ($fracionado->total_bruto ?? 0));
$diferenca = $valorBrutoOriginal - $valorFracionado;
$podeCriarFracionado = abs($diferenca) >= 0.01;
$valoresConferem = abs($diferenca) < 0.01;
    $temDesconto=abs($valorBrutoOriginal - $valorOriginal)>= 0.01;
    @endphp

    @section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

            <x-page-header title="Orçamento Fracionado" :back-url="route('financeiro.index')">
                @if($podeCriarFracionado)
                <form id="formCriarFracionado"
                    action="{{ route('orcamento.fracionado.store', ['orcamento' => CryptHelper::encrypt($orcamento->id_orcamento)]) }}"
                    method="POST">
                    @csrf
                    <x-header-action type="submit" id="btnCriarFracionado">
                        Criar Orçamento Fracionado
                    </x-header-action>
                </form>
                @endif
            </x-page-header>

            <div class="px-6 sm:px-8 pt-6">
                <x-alert-flash />
            </div>

            <div class="px-6 sm:px-8 pt-4 pb-8">

                <x-info-card
                    title="Informações do Orçamento Principal"
                    :name="'Orçamento #' . $orcamento->id_orcamento"
                    type="Orçamento"
                    :fields="[
                    [
                        'label' => 'Cód. Interno',
                        'value' => $orcamento->orc_cod_interno ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cód. Fábrica',
                        'value' => $orcamento->orc_cod_fabrica ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cliente',
                        'value' => $orcamento->clienteOrcamento->clie_orc_nome ?? 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Status',
                        'value' => ucfirst($orcamento->orc_status),
                    ],
                    [
                        'label' => 'Fracionados Criados',
                        'value' => $orcamento->fracionados->count(),
                    ],
                ]" />

                @if($orcamento->fracionados->isNotEmpty())
                <div class="mt-6">
                    <x-summary-cards :cards="array_values(array_filter([
                        $temDesconto ? [
                            'title' => 'Valor Bruto',
                            'value' => 'R$ ' . number_format($valorBrutoOriginal, 2, ',', '.'),
                            'type' => 'warning',
                        ] : null,
                        [
                            'title' => 'Valor com Desconto',
                            'value' => 'R$ ' . number_format($valorOriginal, 2, ',', '.'),
                            'type' => 'info',
                        ],
                        [
                            'title' => 'Total Fracionado',
                            'value' => 'R$ ' . number_format($valorFracionado, 2, ',', '.'),
                            'type' => 'primary',
                        ],
                        [
                            'title' => 'Diferença',
                            'value' => 'R$ ' . number_format(abs($diferenca), 2, ',', '.'),
                            'type' => $valoresConferem ? 'success' : 'danger',
                        ],
                    ]))" />
                </div>

                @if(!$valoresConferem)
                <div class="mt-4 mb-2 flex items-start gap-3 px-4 py-3 rounded-lg bg-yellow-50 border border-yellow-200">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.518 11.591c.75 1.334-.213 2.99-1.742 2.99H3.48c-1.53 0-2.492-1.656-1.743-2.99L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 00-2 0zm-1-7a1 1 0 00-1 1v3a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800">Atenção</p>
                        <p class="text-sm text-yellow-700 mt-1">
                            O valor dos orçamentos fracionados ainda não confere com o valor bruto do orçamento principal.
                        </p>
                    </div>
                </div>
                @endif
                @endif

                <div class="mt-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                        <h2 class="text-lg font-bold text-gray-800">Lista de orçamentos fracionados</h2>
                        <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                            <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                            <span class="text-xs font-semibold text-orange-700">
                                {{ $orcamento->fracionados->count() }} fração(ões)
                            </span>
                        </div>
                    </div>

                    @if($orcamento->fracionados->isEmpty())
                    @if($podeCriarFracionado)
                    <x-empty-state
                        title="Nenhum orçamento fracionado"
                        message="Ainda não existem orçamentos fracionados criados para este orçamento."
                        route="orcamento.fracionado.store"
                        :route-params="['orcamento' => CryptHelper::encrypt($orcamento->id_orcamento)]"
                        method="POST"
                        form-id="formCadastrarFracionado"
                        button-text="Criar orçamento fracionado" />
                    @endif
                    @else
                    <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead style="background-color:#343A40;">
                                    <tr>
                                        <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Fração</th>
                                        <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Orçamento</th>
                                        <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Códigos</th>
                                        <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Status</th>
                                        <th class="px-5 py-4 text-right text-[11px] font-bold text-white uppercase tracking-wider">Valor Total</th>
                                        <th class="px-2 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($orcamento->fracionados as $fracionado)
                                    @php
                                    $valorAtualFracionado = (float) ($fracionado->total_bruto ?? 0);
                                    $podeProsseguirStatus = in_array($fracionado->orc_status, ['pendente', 'pedido fabrica', 'transportadora']);
                                    $statusTipo = [
                                    'pendente' => 'yellow',
                                    'pedido fabrica' => 'purple',
                                    'transportadora' => 'blue',
                                    'entregue' => 'green',
                                    ][$fracionado->orc_status] ?? 'gray';
                                    $statusNome = [
                                    'pendente' => 'Pendente',
                                    'pedido fabrica' => 'Pedido fábrica',
                                    'transportadora' => 'Transportadora',
                                    'entregue' => 'Entregue',
                                    ][$fracionado->orc_status] ?? ucfirst($fracionado->orc_status);
                                    @endphp

                                    <tr class="group hover:bg-orange-50/40 transition">
                                        <td class="px-5 py-5 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                                    <x-icons.document />
                                                </div>
                                                <span class="text-sm font-bold text-gray-800">#{{ $fracionado->orc_fracao }}</span>
                                            </div>
                                        </td>

                                        <td class="px-5 py-5 whitespace-nowrap">
                                            <span class="text-sm font-semibold text-gray-700">
                                                #{{ $fracionado->id_orcamento_fracionado }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-5 whitespace-nowrap">
                                            <div class="space-y-1.5">
                                                <div class="flex items-center gap-2 text-xs">
                                                    <span class="w-8 text-[10px] font-bold text-gray-400">INT.</span>
                                                    <span class="font-semibold text-gray-700">{{ $fracionado->orc_cod_interno ?: 'N/D' }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs">
                                                    <span class="w-8 text-[10px] font-bold text-gray-400">FAB.</span>
                                                    <span class="font-semibold text-gray-700">{{ $fracionado->orc_cod_fabrica ?: 'N/D' }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-5 py-5 whitespace-nowrap">
                                            <x-badge :type="$statusTipo" :text="$statusNome" />
                                        </td>

                                        <td class="px-5 py-5 text-right whitespace-nowrap">
                                            <span class="text-sm font-bold text-gray-800">
                                                R$ {{ number_format($valorAtualFracionado, 2, ',', '.') }}
                                            </span>
                                        </td>

                                        <td class="px-2 py-5 text-center whitespace-nowrap">
                                            <div class="flex justify-center gap-2 flex-wrap">
                                                @if($podeProsseguirStatus && $valorAtualFracionado > 0 && $fracionado->orc_status !== 'entregue')
                                                @if($fracionado->orc_status === 'pendente')
                                                <form
                                                    action="{{ route('fluxo_caixa.storeFluxo') }}"
                                                    method="POST"
                                                    class="form-prosseguir-fracionado"
                                                    data-status="{{ $fracionado->orc_status }}">
                                                    @csrf
                                                    <button
                                                        type="button"
                                                        class="btn-prosseguir-fracionado inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-purple-800 bg-purple-200 border border-purple-300 hover:bg-purple-300 hover:text-purple-900 transition"
                                                        data-id-fracionado="{{ CryptHelper::encrypt($fracionado->id_orcamento_fracionado) }}"
                                                        data-orcamento="{{ CryptHelper::encrypt($fracionado->orcamento_id_orcamento) }}"
                                                        data-orcamento-display="{{ $fracionado->orcamento_id_orcamento }}"
                                                        data-valor="{{ $fracionado->total_bruto }}"
                                                        data-cod-fabrica="{{ $fracionado->orc_cod_fabrica }}">
                                                        Prosseguir Status
                                                    </button>
                                                </form>
                                                @else
                                                <form
                                                    action="{{ route('orcamento.fracionado.prosseguir', CryptHelper::encrypt($fracionado->id_orcamento_fracionado)) }}"
                                                    method="POST"
                                                    class="form-prosseguir-fracionado">
                                                    @csrf
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold text-purple-800 bg-purple-200 border border-purple-300 hover:bg-purple-300 hover:text-purple-900 transition">
                                                        Prosseguir Status
                                                    </button>
                                                </form>
                                                @endif
                                                @endif

                                                <x-table-actions
                                                    :show-route="route('detalhes_orcamento_fracionado.index', CryptHelper::encrypt($fracionado->id_orcamento_fracionado))"
                                                    show-text="Detalhes"
                                                    :budgets-route="route('orcamento.fracionado.visualizar', CryptHelper::encrypt($fracionado->id_orcamento_fracionado))"
                                                    budgets-text="Gerar Orçamento"
                                                    :edit-route="route('orcamento.fracionado.edit', CryptHelper::encrypt($fracionado->id_orcamento_fracionado))"
                                                    edit-text="Editar"
                                                    :delete-action="$fracionado->orc_status === 'pendente' ? route('orcamento.fracionado.destroy', CryptHelper::encrypt($fracionado->id_orcamento_fracionado)) : null"
                                                    delete-id="formExcluirFracionado{{ $fracionado->id_orcamento_fracionado }}"
                                                    delete-modal="modalExcluirFracionado" />

                                                @if($fracionado->orc_status === 'pendente')
                                                <form
                                                    id="formExcluirFracionado{{ $fracionado->id_orcamento_fracionado }}"
                                                    action="{{ route('orcamento.fracionado.destroy', CryptHelper::encrypt($fracionado->id_orcamento_fracionado)) }}"
                                                    method="POST"
                                                    class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
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
                </div>
            </div>
        </div>

        <x-modal-confirmacao
            id="modalExcluirFracionado"
            titulo="Excluir orçamento fracionado"
            mensagem="Deseja realmente apagar este orçamento fracionado?"
            textoConfirmar="Excluir" />

        <div id="modalAnalise" class="fixed inset-0 bg-black/50 hidden items-center justify-center overflow-auto z-50 px-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl my-8 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-800">Lançar no Fluxo de Caixa</h2>
                    <button type="button" id="cancelarModal" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                </div>

                <form id="formModalFluxo" action="{{ route('fluxo_caixa.storeFluxo') }}" method="POST">
                    @csrf

                    <div class="p-6">
                        <div class="flex items-start gap-3 px-4 py-3 rounded-lg bg-yellow-50 border border-yellow-200 mb-6">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.518 11.591c.75 1.334-.213 2.99-1.742 2.99H3.48c-1.53 0-2.492-1.656-1.743-2.99L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 00-2 0zm-1-7a1 1 0 00-1 1v3a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-yellow-800">Atenção</p>
                                <p class="text-sm text-yellow-700 mt-1">
                                    O status do orçamento fracionado só será atualizado após o lançamento do fluxo de caixa.
                                </p>
                            </div>
                        </div>

                        <div class="bg-orange-50 border border-orange-200 rounded-xl p-5 mb-6">
                            <h3 class="text-sm font-bold text-orange-700 uppercase tracking-wide mb-4">Informações do Lançamento</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Orçamento Fracionado</p>
                                    <p id="orcamentoModal" class="font-semibold text-gray-900"></p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Tipo de Despesa</p>
                                    <p class="font-semibold text-gray-900">Variável</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Tipo</p>
                                    <p class="font-semibold text-gray-900">Despesa UP</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Movimentação</p>
                                    <p class="font-semibold text-gray-900">Saída</p>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="flu_tipo_despesa" value="Variavel">
                        <input type="hidden" name="flu_id_tipo" value="{{ $tipoDespesaUP->id_tipo_fluxo ?? '' }}">
                        <input type="hidden" name="flu_id_movimentacao" value="{{ $movSaida->id_movimentacao ?? '' }}">
                        <input type="hidden" name="id_orcamento_fracionado" id="idOrcamentoFracionadoModal">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Data</label>
                                <input type="date" name="flu_data_despesa" id="modalData" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Conta Bancária</label>
                                <select name="conta_bancaria_id" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                                    <option value="">Selecione</option>
                                    @foreach($contas as $conta)
                                    <option value="{{ $conta->id_conta }}">
                                        {{ $conta->conta_nome_banco }} - {{ $conta->numero_conta_corrente }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Valor</label>
                                <input type="text" id="valorMask" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" placeholder="R$ 0,00" required>
                                <input type="hidden" name="flu_valor" id="valorReal">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tipo Fiscal</label>
                                <select name="flu_tipo_fiscal" id="tipoFiscalModal" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
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
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Num. Documento</label>
                                <input type="text" name="flu_num_doc" id="numDocModal" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" placeholder="Opcional" disabled>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Descrição</label>
                                <input type="text" name="flu_desc" value="Pagamento do Orçamento Fracionado" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-2">
                        <button type="button" id="cancelarModalFooter" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 transition">
                            Cancelar
                        </button>
                        <button type="submit" id="btnSalvarFluxo" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white rounded-lg transition hover:brightness-90" style="background-color:#16A34A;">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <x-modal-confirmacao
            id="modalConfirmarFluxo"
            titulo="Lançar no Fluxo de Caixa"
            mensagem="Deseja realmente lançar este fluxo de caixa e avançar o status do orçamento fracionado?"
            textoConfirmar="Confirmar" />

        <div id="modalAvisoFluxo" class="fixed inset-0 bg-black/50 hidden items-center justify-center overflow-auto z-50 px-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-800">Atenção</h2>
                    <button type="button" id="fecharModalAviso" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                </div>

                <div class="p-6">
                    <div class="flex items-start gap-3 px-4 py-3 rounded-lg bg-yellow-50 border border-yellow-200">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l6.518 11.591c.75 1.334-.213 2.99-1.742 2.99H3.48c-1.53 0-2.492-1.656-1.743-2.99L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 00-2 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <p id="mensagemModalAviso" class="text-sm text-yellow-700"></p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button type="button" id="btnFecharModalAviso" class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                        Fechar
                    </button>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const formCriar = document.getElementById('formCriarFracionado');
                const btnCriar = document.getElementById('btnCriarFracionado');

                if (formCriar && btnCriar) {
                    formCriar.addEventListener('submit', function() {
                        if (btnCriar.disabled) return false;
                        btnCriar.disabled = true;
                        btnCriar.innerText = 'SALVANDO...';
                        btnCriar.classList.add('opacity-70', 'cursor-not-allowed');
                    });
                }

                const formCadastrar = document.getElementById('formCadastrarFracionado');

                if (formCadastrar) {
                    formCadastrar.addEventListener('submit', function() {
                        const btnCadastrar = this.querySelector('button[type="submit"]');

                        if (!btnCadastrar || btnCadastrar.disabled) return false;

                        btnCadastrar.disabled = true;
                        btnCadastrar.innerText = 'SALVANDO...';
                        btnCadastrar.classList.add('opacity-70', 'cursor-not-allowed');
                    });
                }

                const modal = document.getElementById('modalAnalise');
                const btnCancelar = document.getElementById('cancelarModal');
                const btnCancelarFooter = document.getElementById('cancelarModalFooter');
                const formModal = document.getElementById('formModalFluxo');
                const btnSalvarFluxo = document.getElementById('btnSalvarFluxo');
                const idFracionadoInput = document.getElementById('idOrcamentoFracionadoModal');
                const orcamentoModalTexto = document.getElementById('orcamentoModal');
                const valorMask = document.getElementById('valorMask');
                const valorReal = document.getElementById('valorReal');
                const tipoFiscalModal = document.getElementById('tipoFiscalModal');
                const numDocModal = document.getElementById('numDocModal');
                const modalAviso = document.getElementById('modalAvisoFluxo');
                const fecharModalAviso = document.getElementById('fecharModalAviso');
                const btnFecharModalAviso = document.getElementById('btnFecharModalAviso');
                const mensagemModalAviso = document.getElementById('mensagemModalAviso');

                function abrirModalAviso(mensagem) {
                    mensagemModalAviso.textContent = mensagem;
                    modalAviso.classList.remove('hidden');
                    modalAviso.classList.add('flex');
                }

                function fecharModalAvisoFluxo() {
                    modalAviso.classList.add('hidden');
                    modalAviso.classList.remove('flex');
                }

                fecharModalAviso.addEventListener('click', fecharModalAvisoFluxo);
                btnFecharModalAviso.addEventListener('click', fecharModalAvisoFluxo);

                modalData.value = new Date().toISOString().split('T')[0];

                function fecharModalFluxo() {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }

                btnCancelar.addEventListener('click', fecharModalFluxo);
                btnCancelarFooter.addEventListener('click', fecharModalFluxo);

                document.querySelectorAll('.btn-prosseguir-fracionado').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const form = this.closest('form');
                        const idFracionado = this.dataset.idFracionado;
                        const orcamento = this.dataset.orcamentoDisplay;
                        const valor = parseFloat(this.dataset.valor || 0);
                        const status = form.dataset.status;
                        const codFabrica = this.dataset.codFabrica;

                        if (valor <= 0) {
                            abrirModalAviso('Não é possível prosseguir com o status. O valor total atual do orçamento fracionado deve ser maior que R$ 0,00.');
                            return;
                        }

                        if (status === 'pendente' && (!codFabrica || codFabrica.trim() === '')) {
                            abrirModalAviso('Não é possível enviar para Pedido fábrica. Informe primeiro o Código de Fábrica no orçamento fracionado.');
                            return;
                        }

                        idFracionadoInput.value = idFracionado;
                        orcamentoModalTexto.textContent = orcamento;
                        valorReal.value = '';
                        valorMask.value = '';
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    });
                });

                valorMask.addEventListener('input', function() {
                    let valor = this.value.replace(/\D/g, '');

                    if (!valor) {
                        this.value = '';
                        valorReal.value = '';
                        return;
                    }

                    valor = (parseInt(valor, 10) / 100).toFixed(2);
                    valorReal.value = valor;
                    this.value = Number(valor).toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    });
                });

                tipoFiscalModal.addEventListener('change', function() {
                    numDocModal.disabled = !this.value;
                    if (!this.value) numDocModal.value = '';
                });

                formModal.addEventListener('submit', function(e) {
                    e.preventDefault();

                    abrirModal('modalConfirmarFluxo', function() {
                        btnSalvarFluxo.disabled = true;
                        btnSalvarFluxo.innerText = 'SALVANDO...';
                        btnSalvarFluxo.classList.add('opacity-70', 'cursor-not-allowed');
                        formModal.submit();
                    });
                });
            });
        </script>
        @endpush

        @endsection