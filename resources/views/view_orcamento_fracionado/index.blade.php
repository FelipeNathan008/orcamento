@extends('layouts.app_financeiro')

@section('title', 'Orçamento Fracionado')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- CABEÇALHO --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Orçamento Fracionado
        </h1>

        @php
        $valorBrutoOriginal = (float) $orcamento->total_bruto;

        $valorFracionado = $orcamento->fracionados->sum(function ($fracionado) {
        return (float) ($fracionado->total_bruto ?? 0);
        });

        $diferencaCriacao = $valorBrutoOriginal - $valorFracionado;

        $podeCriarFracionado = abs($diferencaCriacao) >= 0.01;
        @endphp

        <div class="flex items-center gap-3">

            <a href="{{ route('financeiro.index') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition">
                VOLTAR
            </a>

            @if($podeCriarFracionado)
            <form id="formCriarFracionado"
                action="{{ route('orcamento.fracionado.store', ['orcamento' => $orcamento->id_orcamento]) }}"
                method="POST">
                @csrf

                <button
                    id="btnCriarFracionado"
                    type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white hover:brightness-90 transition"
                    style="background-color:#EA792D;">
                    Criar Orçamento Fracionado
                </button>
            </form>
            @endif

        </div>

    </div>

    <x-alert-flash />

    {{-- INFORMAÇÕES DO ORÇAMENTO --}}
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações do Orçamento Principal
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <p class="text-gray-600">ID</p>
                    <p class="font-semibold">
                        {{ $orcamento->id_orcamento }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-600">Cód. Interno</p>
                    <p class="font-semibold">
                        {{ $orcamento->orc_cod_interno ?: 'N/D' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-600">Cód. Fábrica</p>
                    <p class="font-semibold">
                        {{ $orcamento->orc_cod_fabrica ?: 'N/D' }}
                    </p>
                </div>
            </div>

            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold text-gray-900">
                    {{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'N/D' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Status</p>
                <p class="font-semibold text-gray-900">
                    {{ $orcamento->orc_status }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Fracionados Criados</p>
                <p class="font-semibold text-gray-900">
                    {{ $orcamento->fracionados->count() }}
                </p>
            </div>

        </div>

    </div>

    <!-- <div id="image-warning-message" class="mt-2 text-sm text-yellow-600">
        <i class="fas fa-exclamation-triangle mr-1"></i>Não é possível Prosseguir Status do Financeiro enquanto os valores não conferirem.
    </div> -->

    {{-- VALORES --}}

    @if($orcamento->fracionados->isNotEmpty())

    @php
    $valorBrutoOriginal = (float) $orcamento->total_bruto;
    $valorOriginal = (float) $orcamento->total_com_desconto;

    $valorFracionado = $orcamento->fracionados->sum(function ($fracionado) {
    return (float) $fracionado->total_bruto;
    });

    // A diferença sempre é calculada com base no valor bruto
    $diferenca = $valorBrutoOriginal - $valorFracionado;

    $valoresConferem = abs($diferenca) < 0.01;

        // Verifica se existe desconto no orçamento original
        $temDesconto=abs($valorBrutoOriginal - $valorOriginal)>= 0.01;
        @endphp

        {{-- VALORES --}}
        <div class="mb-6 p-4 bg-gray-100 rounded-lg flex flex-col md:flex-row justify-around items-center text-center gap-4">

            {{-- VALOR BRUTO - SOMENTE SE HOUVER DESCONTO --}}
            @if($temDesconto)
            <div>
                <span class="font-bold text-lg text-gray-500">
                    Valor Bruto
                </span>

                <span class="block text-gray-900 text-lg">
                    R$ {{ number_format($valorBrutoOriginal, 2, ',', '.') }}
                </span>
            </div>

            {{-- VALOR ORIGINAL --}}
            <div>
                <span class="font-bold text-lg text-yellow-600">
                    Valor com Desconto
                </span>

                <span class="block text-gray-900 text-lg">
                    R$ {{ number_format($valorOriginal, 2, ',', '.') }}
                </span>
            </div>

            @else

            {{-- VALOR ORIGINAL --}}
            <div>
                <span class="font-bold text-lg text-yellow-600">
                    Valor com Desconto
                </span>

                <span class="block text-gray-900 text-lg">
                    R$ {{ number_format($valorOriginal, 2, ',', '.') }}
                </span>
            </div>
            @endif

            {{-- TOTAL FRACIONADO --}}
            <div>
                <span class="font-bold text-lg text-blue-600">
                    Total Fracionado
                </span>

                <span class="block text-gray-900 text-lg">
                    R$ {{ number_format($valorFracionado, 2, ',', '.') }}
                </span>
            </div>

            {{-- DIFERENÇA --}}
            <div>
                <span class="font-bold text-lg {{ $valoresConferem ? 'text-green-600' : 'text-red-600' }}">
                    Diferença
                </span>

                <span class="block text-gray-900 text-lg">
                    R$ {{ number_format(abs($diferenca), 2, ',', '.') }}
                </span>
            </div>

        </div>

        @endif


        {{-- LISTAGEM --}}
        <h2 class="text-2xl font-bold text-gray-800 mb-5">
            Orçamentos Fracionados
        </h2>

        @if($orcamento->fracionados->isEmpty())

        <p class="text-gray-600 text-center py-8">
            Nenhum orçamento fracionado criado.
        </p>

        @else

        <div class="w-full rounded-lg shadow-table-shadow-image overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-table-header-bg">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Fração</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">ID Orçamento Fracionado</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-white uppercase">Valor Total Atual</th>
                        <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase">Ações</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">

                    @foreach($orcamento->fracionados as $fracionado)

                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-4 py-4 text-sm font-medium">
                            #{{ $fracionado->orc_fracao }}
                        </td>

                        {{-- ID Orçamento --}}
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">

                            <div>
                                {{ $fracionado->id_orcamento_fracionado ?? 'N/A' }}
                            </div>

                            @if($fracionado->orc_cod_interno || $fracionado->orc_cod_fabrica)

                            <div class="text-xs text-gray-500 mt-1">

                                @if($fracionado->orc_cod_interno)
                                Interno: {{ $fracionado->orc_cod_interno }}
                                @endif

                                @if(
                                $fracionado->orc_cod_interno &&
                                $fracionado->orc_cod_fabrica
                                )
                                <span class="mx-1">|</span>
                                @endif

                                @if($fracionado->orc_cod_fabrica)
                                Fábrica: {{ $fracionado->orc_cod_fabrica }}
                                @endif

                            </div>

                            @endif

                        </td>

                        <td class="px-4 py-4 text-sm">

                            @php
                            $statusClass = match($fracionado->orc_status) {
                            'pendente' => 'bg-yellow-400',
                            'pedido fabrica' => 'bg-purple-400',
                            'transportadora' => 'bg-indigo-400',
                            'entregue' => 'bg-green-400',
                            default => 'bg-gray-400'
                            };

                            $statusNome = match($fracionado->orc_status) {
                            'pendente' => 'Pendente',
                            'pedido fabrica' => 'Pedido fábrica',
                            'transportadora' => 'Transportadora',
                            'entregue' => 'Entregue',
                            default => ucfirst($fracionado->orc_status)
                            };
                            @endphp

                            <span class="relative inline-block px-3 py-1 font-semibold text-gray-900">
                                <span class="absolute inset-0 opacity-50 rounded-full {{ $statusClass }}"></span>
                                <span class="relative">{{ $statusNome }}</span>
                            </span>

                        </td>

                        {{-- VALOR TOTAL --}}
                        <td class="px-4 py-4 text-sm text-right font-semibold text-gray-900 whitespace-nowrap">
                            R$ {{ number_format($fracionado->total_bruto, 2, ',', '.') }}
                        </td>

                        <td class="px-2 py-4 text-center">

                            <div class="flex justify-center gap-2 flex-wrap">

                                @php
                                $valorAtualFracionado = (float) ($fracionado->total_bruto ?? 0);
                                $podeProsseguirStatus = in_array($fracionado->orc_status, [
                                'pendente',
                                'pedido fabrica',
                                'transportadora'
                                ]);
                                @endphp

                                @if($podeProsseguirStatus)
                                @if($valorAtualFracionado > 0)

                                @if($fracionado->orc_status !== 'entregue')

                                @if($fracionado->orc_status === 'pendente')

                                {{-- PENDENTE: precisa abrir o modal e lançar no fluxo --}}
                                <form
                                    action="{{ route('fluxo_caixa.storeFluxo') }}"
                                    method="POST"
                                    class="form-prosseguir-fracionado"
                                    data-status="{{ $fracionado->orc_status }}">
                                    @csrf

                                    <button
                                        type="button"
                                        class="btn-prosseguir-fracionado px-2 py-1 text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                                        data-id-fracionado="{{ $fracionado->id_orcamento_fracionado }}"
                                        data-orcamento="{{ $fracionado->orcamento_id_orcamento }}"
                                        data-valor="{{ $fracionado->total_bruto }}"
                                        data-cod-fabrica="{{ $fracionado->orc_cod_fabrica }}">
                                        Prosseguir Status
                                    </button>

                                </form>

                                @else

                                {{-- DEMAIS STATUS: NÃO abre modal, apenas avança --}}
                                <form
                                    action="{{ route('orcamento.fracionado.prosseguir', $fracionado->id_orcamento_fracionado) }}"
                                    method="POST"
                                    class="form-prosseguir-fracionado"
                                    data-status="{{ $fracionado->orc_status }}">
                                    @csrf

                                    <button
                                        type="submit"
                                        class="px-2 py-1 text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                                        onclick="return confirm('Tem certeza que deseja prosseguir o status?')">
                                        Prosseguir Status
                                    </button>

                                </form>

                                @endif

                                @endif

                                @endif
                                @endif

                                <a href="{{ route('detalhes_orcamento_fracionado.index', $fracionado->id_orcamento_fracionado) }}"
                                    class="relative inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">

                                    @if($fracionado->detalhes_orcamento_fracionado_count > 0)
                                    <span class="absolute -top-2 -right-2 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-orange-500 rounded-full">
                                        {{ $fracionado->detalhes_orcamento_fracionado_count }}
                                    </span>
                                    @endif

                                    Detalhes
                                </a>

                                <a href="{{ route('orcamento.fracionado.visualizar', $fracionado->id_orcamento_fracionado) }}"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600">
                                    Gerar Orçamento
                                </a>

                                <a href="{{ route('orcamento.fracionado.edit',$fracionado->id_orcamento_fracionado) }}"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover">
                                    Editar
                                </a>

                                @if($fracionado->orc_status === 'pendente')

                                <form id="formExcluirFracionado{{ $fracionado->id_orcamento_fracionado }}"
                                    action="{{ route('orcamento.fracionado.destroy', $fracionado->id_orcamento_fracionado) }}"
                                    method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="button"
                                        onclick="abrirModal('modalExcluirFracionado', () => document.getElementById('formExcluirFracionado{{ $fracionado->id_orcamento_fracionado }}').submit())"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                        Excluir
                                    </button>
                                </form>

                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
</div>

<x-modal-confirmacao
    id="modalExcluirFracionado"
    titulo="Excluir orçamento fracionado"
    mensagem="Deseja realmente apagar este orçamento fracionado?"
    textoConfirmar="Excluir" />
{{-- MODAL FLUXO DE CAIXA --}}

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
                            O status do orçamento fracionado só será atualizado após o lançamento do fluxo de caixa.
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
                            <p class="text-gray-600">Orçamento Fracionado</p>
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
                    <input type="hidden" name="flu_id_tipo" value="{{ $tipoDespesaUP->id_tipo_fluxo ?? '' }}">
                    <input type="hidden" name="flu_id_movimentacao" value="{{ $movSaida->id_movimentacao ?? '' }}">

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
                    <textarea name="flu_desc" placeholder="Pagamento do Orçamento Fracionado"
                        class="block w-full px-4 py-2 bg-white text-gray-800 rounded-md border border-gray-300"
                        rows="3"
                        required></textarea>
                </div>
            </div>

            <input type="hidden" name="id_orcamento_fracionado" id="idOrcamentoFracionadoModal">

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" id="cancelarModal"
                    class="px-4 py-2 bg-gray-300 rounded">
                    Cancelar
                </button>

                <button type="submit" id="btnSalvarFluxo"
                    class="px-4 py-2 bg-green-600 text-white rounded">
                    Salvar
                </button>
            </div>

        </form>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const formCriar = document.getElementById('formCriarFracionado');
        const btnCriar = document.getElementById('btnCriarFracionado');

        if (formCriar && btnCriar) {
            formCriar.addEventListener('submit', function() {

                if (btnCriar.disabled) {
                    return false;
                }

                btnCriar.disabled = true;
                btnCriar.innerText = 'SALVANDO...';
                btnCriar.classList.add('opacity-70', 'cursor-not-allowed');
            });
        }

    });

    document.addEventListener('DOMContentLoaded', function() {

        const modal = document.getElementById('modalAnalise');
        const btnCancelar = document.getElementById('cancelarModal');
        const formModal = document.getElementById('formModalFluxo');
        const btnSalvarFluxo = document.getElementById('btnSalvarFluxo');

        const idFracionadoInput = document.getElementById('idOrcamentoFracionadoModal');
        const orcamentoModalTexto = document.getElementById('orcamentoModal');
        const valorMask = document.getElementById('valorMask');
        const valorReal = document.getElementById('valorReal');
        const tipoFiscalModal = document.getElementById('tipoFiscalModal');
        const numDocModal = document.getElementById('numDocModal');

        // DATA AUTOMÁTICA
        document.getElementById('modalData').value = new Date().toISOString().split('T')[0];

        document.querySelectorAll('.btn-prosseguir-fracionado').forEach(function(btn) {

            btn.addEventListener('click', function() {

                const form = this.closest('form');

                const idFracionado = this.dataset.idFracionado;
                const orcamento = this.dataset.orcamento;
                const valor = parseFloat(this.dataset.valor || 0);
                const status = form.dataset.status;
                const codFabrica = this.dataset.codFabrica;

                if (valor <= 0) {
                    alert(
                        'Não é possível prosseguir com o status. ' +
                        'O valor total atual do orçamento fracionado deve ser maior que R$ 0,00.'
                    );
                    return;
                }

                if (status === 'pendente' && (!codFabrica || codFabrica.trim() === '')) {
                    alert(
                        'Não é possível enviar para Pedido fábrica. ' +
                        'Informe primeiro o Código de Fábrica no orçamento fracionado.'
                    );
                    return;
                }

                if (status === 'pendente') {

                    idFracionadoInput.value = idFracionado;
                    orcamentoModalTexto.textContent = orcamento;

                    valorReal.value = '';
                    valorMask.value = '';

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }
            });
        });

        // MÁSCARA DE VALOR
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
            if (this.value) {
                numDocModal.disabled = false;
            } else {
                numDocModal.disabled = true;
                numDocModal.value = '';
            }
        });

        // CANCELAR
        btnCancelar.addEventListener('click', function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        formModal.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!confirm(
                    'Tem certeza que deseja lançar este fluxo de caixa e avançar o status do orçamento fracionado?'
                )) {
                return;
            }

            // evita duplo clique
            btnSalvarFluxo.disabled = true;
            btnSalvarFluxo.innerText = 'SALVANDO...';
            btnSalvarFluxo.classList.add('opacity-70', 'cursor-not-allowed');

            this.submit();
        });

    });
</script>
@endsection