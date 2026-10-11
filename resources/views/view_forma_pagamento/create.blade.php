@extends('layouts.app_financeiro')

@section('title', 'Cadastrar Nova Forma de Pagamento')

@php
use App\Helpers\CryptHelper;

$financeiroSelecionado = null;
$valorPago = 0;
$valorTotal = 0;
$valorFaltante = 0;

if ($financeiroId) {
$financeiroSelecionado = $financeiros->firstWhere('id_fin', $financeiroId);
if ($financeiroSelecionado) {
$formasDoFinanceiro = $formasPagamento->where('financeiro_id_fin', $financeiroId);
$valorTotal = $financeiroSelecionado->fin_valor_total ?? 0;
$valorPago = $formasDoFinanceiro->sum('forma_valor');
$valorFaltante = max($valorTotal - $valorPago, 0);
}
}
@endphp

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header
            title="Cadastrar Nova Forma de Pagamento"
            :back-url="route('forma_pagamento.index', CryptHelper::encrypt($financeiroId))" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form id="formaPagamentoForm"
            action="{{ route('forma_pagamento.store') }}"
            method="POST"
            class="px-6 sm:px-8 pt-6 pb-8"
            data-valor-faltante="{{ $valorFaltante }}">
            @csrf

            <input type="hidden" name="financeiro_id_fin" value="{{ $financeiroId }}">

            @if($financeiroSelecionado)
            <x-info-card
                title="Financeiro selecionado"
                :name="$financeiroSelecionado->fin_nome_cliente"
                type="Cliente"
                :fields="[
                    [
                        'label' => 'ID Orçamento',
                        'value' => $financeiroSelecionado->orcamento->id_orcamento ?? 'N/D',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cód. Interno',
                        'value' => $financeiroSelecionado->orcamento->orc_cod_interno ?: 'N/D',
                    ],
                    [
                        'label' => 'Cód. Fábrica',
                        'value' => $financeiroSelecionado->orcamento->orc_cod_fabrica ?: 'N/D',
                    ],
                    [
                        'label' => 'Status',
                        'value' => $financeiroSelecionado->fin_status ?: 'N/D',
                        'break' => true,
                    ],
                ]" />
            @endif

            <div id="valorTotalPedido" class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mt-6">
                <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-sm font-semibold text-gray-700">Resumo financeiro</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-200">
                    <div class="px-5 py-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Valor Total</p>
                        <p class="mt-1 text-lg font-semibold text-gray-800">
                            R$ {{ number_format($valorTotal, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="px-5 py-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Valor Pago</p>
                        <p class="mt-1 text-lg font-semibold text-gray-800">
                            R$ {{ number_format($valorPago, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="px-5 py-4 bg-red-50">
                        <p class="text-xs font-semibold text-red-600 uppercase tracking-wide">Valor Faltante</p>
                        <p class="mt-1 text-xl font-bold text-red-700">
                            R$ {{ number_format($valorFaltante, 2, ',', '.') }}
                        </p>
                        <p class="mt-1 text-xs text-red-500">O pagamento não pode ultrapassar este valor.</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6 mt-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-green-100 text-green-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do pagamento</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Preencha as informações da forma de pagamento.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="tipo_pagamento_id_tipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Tipo de Pagamento
                        </label>
                        <select name="tipo_pagamento_id_tipo" id="tipo_pagamento_id_tipo" required
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            <option value="">Selecione</option>
                            @foreach($tiposPagamento as $tipo)
                            <option value="{{ $tipo->id_tipo_pagamento }}"
                                {{ old('tipo_pagamento_id_tipo') == $tipo->id_tipo_pagamento ? 'selected' : '' }}>
                                {{ $tipo->tipo_plano_fin }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="forma_valor" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Valor Total
                        </label>
                        <input type="text" name="forma_valor" id="forma_valor"
                            value="{{ old('forma_valor') }}"
                            placeholder="R$ 0,00"
                            required
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        <p id="msg_valor" class="text-xs text-gray-500 hidden mt-1">Preencha o tipo de pagamento primeiro.</p>
                        <p id="msg_valor_maximo" class="text-xs text-red-600 hidden mt-1"></p>
                    </div>

                    <div>
                        <label for="forma_mes" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Competência (Mês)
                        </label>
                        <input type="number" name="forma_mes" id="forma_mes"
                            value="{{ old('forma_mes') }}"
                            min="1" max="12"
                            placeholder="1 até 12"
                            required
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                    </div>

                    <div>
                        <label for="forma_prazo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Prazo
                        </label>
                        <select name="forma_prazo" id="forma_prazo" required
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            <option value="">Selecione...</option>
                            <option value="Entrada" {{ old('forma_prazo') == 'Entrada' ? 'selected' : '' }}>Entrada</option>
                            <option value="À vista" {{ old('forma_prazo') == 'À vista' ? 'selected' : '' }}>À vista</option>
                            <option value="Parcelado" {{ old('forma_prazo') == 'Parcelado' ? 'selected' : '' }}>Parcelado</option>
                        </select>
                        <p id="msg_prazo" class="text-xs text-gray-500 hidden mt-1">Preencha o valor primeiro.</p>
                    </div>

                    <div>
                        <label for="conta_bancaria_id" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Conta Bancária
                        </label>
                        <select name="conta_bancaria_id" id="conta_bancaria_id" required
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            <option value="">Selecione</option>
                            @foreach($contas as $conta)
                            <option value="{{ $conta->id_conta }}"
                                {{ old('conta_bancaria_id') == $conta->id_conta ? 'selected' : '' }}>
                                {{ $conta->conta_nome_banco }} - {{ $conta->numero_conta_corrente }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="forma_qtd_parcela" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Quantidade de Parcelas
                        </label>
                        <input type="number" name="forma_qtd_parcela" id="forma_qtd_parcela"
                            value="{{ old('forma_qtd_parcela', 1) }}"
                            min="1" max="50"
                            placeholder="1, 2, 3..."
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        <p id="msg_parcelas" class="text-xs text-gray-500 hidden mt-1">Selecione o prazo primeiro.</p>
                        <p id="msg_parcelas_erro" class="text-xs text-red-600 hidden mt-1"></p>
                    </div>
                </div>

                <div id="areaParcelas" class="hidden bg-white border border-gray-200 rounded-xl p-5 sm:p-6 mt-5">
                    <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                            <x-icons.document class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Parcelas geradas</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Configure o vencimento e o intervalo das parcelas.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label for="data_primeira_parcela" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Data da 1ª Parcela
                            </label>
                            <input type="date" id="data_primeira_parcela"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="intervalo_dias_parcela" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Intervalo entre Parcelas
                            </label>
                            <select id="intervalo_dias_parcela"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="5">5 dias</option>
                                <option value="10">10 dias</option>
                                <option value="15">15 dias</option>
                                <option value="20">20 dias</option>
                                <option value="25">25 dias</option>
                                <option value="30" selected>30 dias</option>
                            </select>
                        </div>

                        <div>
                            <label for="valor_parcela_preview" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Valor de Cada Parcela
                            </label>
                            <input type="text" id="valor_parcela_preview"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-lg"
                                readonly>
                        </div>
                    </div>

                    <div id="listaParcelas" class="space-y-2 mt-5"></div>
                    <div id="parcelasHidden"></div>
                </div>

                <div class="hidden md:col-span-2 mt-5" id="campo_data">
                    <label for="forma_data" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                        Data do Pagamento
                    </label>
                    <input type="date" name="forma_data" id="forma_data"
                        value="{{ old('forma_data') }}"
                        max="{{ date('Y-m-d') }}"
                        class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                </div>


                <div class="md:col-span-2 mt-5">
                    <label for="forma_descricao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                        Descrição
                    </label>
                    <textarea name="forma_descricao" id="forma_descricao"
                        rows="4"
                        placeholder="Entrada, Chegada do Material..."
                        required
                        class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none">{{ old('forma_descricao') }}</textarea>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mt-6">
                <div class="flex items-start gap-3">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-yellow-100 text-yellow-600 flex-shrink-0">
                        <svg class="w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.763-1.36 2.723-1.36 3.486 0l5.58 9.968c.75 1.34-.213 2.983-1.743 2.983H4.42c-1.53 0-2.493-1.643-1.743-2.983l5.58-9.968zM10 7a1 1 0 01.993.883L11 8v2a1 1 0 11-2 0V8a1 1 0 011-1zm0 6a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-yellow-800">Atenção antes de salvar</h3>
                        <p class="mt-1 text-sm text-yellow-700">
                            Após o cadastro, os dados desta forma de pagamento
                            <strong>não poderão ser editados</strong>. Confira cuidadosamente
                            os valores, datas, parcelas e demais informações antes de salvar.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="route('forma_pagamento.index', CryptHelper::encrypt($financeiroId))">
                    Voltar para a lista
                </x-secondary-button>
                <x-primary-button type="submit" id="btnSalvarForma" class="px-6">
                    <span id="textoSalvar">Salvar forma de pagamento</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

<x-modal-confirmacao
    id="modalSalvarFormaPagamento"
    titulo="Confirmar cadastro"
    mensagem="Após salvar, os dados desta forma de pagamento NÃO poderão ser editados. Confira os valores, datas, parcelas e demais informações antes de continuar. Deseja realmente salvar esta forma de pagamento?"
    textoConfirmar="Salvar" />

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formaPagamentoForm');
        const btnSalvar = document.getElementById('btnSalvarForma');
        const textoSalvar = document.getElementById('textoSalvar');
        const valorFaltante = parseFloat(form.dataset.valorFaltante || '0');
        const msgValorMaximo = document.getElementById('msg_valor_maximo');
        const msgParcelasErro = document.getElementById('msg_parcelas_erro');
        const selectPrazo = document.getElementById('forma_prazo');
        const inputParcelas = document.getElementById('forma_qtd_parcela');
        const areaParcelas = document.getElementById('areaParcelas');
        const inputDataPrimeiraParcela = document.getElementById('data_primeira_parcela');
        const listaParcelas = document.getElementById('listaParcelas');
        const parcelasHidden = document.getElementById('parcelasHidden');
        const inputValorTotal = document.getElementById('forma_valor');
        const inputValorParcelaPreview = document.getElementById('valor_parcela_preview');
        const campoData = document.getElementById('campo_data');
        const campoDataInput = document.getElementById('forma_data');
        const tipoPagamento = document.getElementById('tipo_pagamento_id_tipo');
        const valor = inputValorTotal;
        const prazo = selectPrazo;
        const parcelas = inputParcelas;
        const dataPagamento = campoDataInput;
        const descricao = document.getElementById('forma_descricao');
        const msgValor = document.getElementById('msg_valor');
        const msgPrazo = document.getElementById('msg_prazo');
        const msgParcelas = document.getElementById('msg_parcelas');
        const inputIntervaloDias = document.getElementById('intervalo_dias_parcela');

        function converterValorBRparaFloat(valor) {
            if (!valor) return 0;
            return parseFloat(valor.replace(/\D/g, '')) / 100;
        }

        function formatarBR(valor) {
            return valor.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function formatarMoedaBR(input) {
            let v = input.value.replace(/\D/g, '');
            if (v === '') {
                input.value = '';
                return;
            }
            v = (parseInt(v) / 100).toFixed(2);
            v = v.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = 'R$ ' + v;
        }

        function validarValorMaximo() {
            const valorDigitado = converterValorBRparaFloat(inputValorTotal.value);
            if (valorDigitado > valorFaltante) {
                msgValorMaximo.textContent = `O valor não pode ser maior que o valor faltante (R$ ${formatarBR(valorFaltante)})`;
                msgValorMaximo.classList.remove('hidden');
                inputValorTotal.setCustomValidity('Valor maior que o valor faltante');
                btnSalvar.disabled = true;
                btnSalvar.classList.add('opacity-50', 'cursor-not-allowed');
                return false;
            }
            msgValorMaximo.classList.add('hidden');
            inputValorTotal.setCustomValidity('');
            btnSalvar.disabled = false;
            btnSalvar.classList.remove('opacity-50', 'cursor-not-allowed');
            return true;
        }

        function validarParcelas() {
            const valor = inputParcelas.value;
            if (valor === '') {
                msgParcelasErro.textContent = 'Informe a quantidade de parcelas.';
                msgParcelasErro.classList.remove('hidden');
                inputParcelas.setCustomValidity('Inválido');
                btnSalvar.disabled = true;
                return false;
            }
            const numero = Number(valor);
            if (!Number.isInteger(numero) || numero < 1 || numero > 50) {
                msgParcelasErro.textContent = 'A quantidade de parcelas deve ser um número inteiro entre 1 e 50.';
                msgParcelasErro.classList.remove('hidden');
                inputParcelas.setCustomValidity('Inválido');
                btnSalvar.disabled = true;
                return false;
            }
            msgParcelasErro.classList.add('hidden');
            inputParcelas.setCustomValidity('');
            btnSalvar.disabled = false;
            return true;
        }

        function calcularValoresParcelas() {
            const total = converterValorBRparaFloat(inputValorTotal.value);
            const qtd = parseInt(inputParcelas.value);
            if (!total || !qtd || qtd <= 0) return [];
            const valorBase = Math.floor((total / qtd) * 100) / 100;
            const valores = new Array(qtd).fill(valorBase);
            const somaBase = valorBase * qtd;
            const diferenca = Number((total - somaBase).toFixed(2));
            valores[qtd - 1] = Number((valores[qtd - 1] + diferenca).toFixed(2));
            return valores;
        }

        function adicionarDias(data, dias) {
            const nova = new Date(data);
            nova.setDate(nova.getDate() + dias);
            return nova;
        }

        function gerarParcelas() {
            listaParcelas.innerHTML = '';
            parcelasHidden.innerHTML = '';
            const qtd = parseInt(inputParcelas.value);
            const dataInicial = inputDataPrimeiraParcela.value;
            const valores = calcularValoresParcelas();
            const intervaloDias = parseInt(inputIntervaloDias.value) || 30;

            if (!qtd || !dataInicial || valores.length === 0) {
                inputValorParcelaPreview.value = '';
                return;
            }

            inputValorParcelaPreview.value = 'R$ ' + formatarBR(valores[0]);
            let data = new Date(dataInicial + 'T00:00:00');

            for (let i = 1; i <= qtd; i++) {
                const dia = String(data.getDate()).padStart(2, '0');
                const mes = String(data.getMonth() + 1).padStart(2, '0');
                const ano = data.getFullYear();
                const valorParcela = valores[i - 1];

                listaParcelas.innerHTML += `
                <div class="p-3 bg-white rounded-lg border border-gray-200 flex justify-between items-center text-sm">
                    <span class="font-medium text-gray-700">Parcela ${i}</span>
                    <span class="text-gray-600"><strong>${dia}/${mes}/${ano}</strong> - R$ ${formatarBR(valorParcela)}</span>
                </div>
            `;

                parcelasHidden.innerHTML += `
                <input type="hidden" name="datas_parcelas[]" value="${ano}-${mes}-${dia}">
                <input type="hidden" name="valores_parcelas[]" value="${valorParcela}">
            `;

                data = adicionarDias(data, intervaloDias);
            }
        }

        function controlarCampos() {
            const prazoValor = selectPrazo.value;
            const qtd = parseInt(inputParcelas.value) || 0;
            const usaParcelas = prazoValor === 'Parcelado' || (prazoValor === 'Entrada' && qtd > 1);
            const usaDataUnica = prazoValor === 'À vista' || (prazoValor === 'Entrada' && qtd <= 1);

            if (usaParcelas) {
                inputParcelas.readOnly = false;
                areaParcelas.classList.remove('hidden');
                campoData.classList.add('hidden');
                campoDataInput.required = false;
                campoDataInput.value = '';
                inputDataPrimeiraParcela.required = true;
                gerarParcelas();
            } else if (usaDataUnica) {
                if (prazoValor === 'À vista') {
                    inputParcelas.value = 1;
                    inputParcelas.readOnly = true;
                } else {
                    inputParcelas.readOnly = false;
                }
                areaParcelas.classList.add('hidden');
                parcelasHidden.innerHTML = '';
                listaParcelas.innerHTML = '';
                inputValorParcelaPreview.value = '';
                inputDataPrimeiraParcela.required = false;
                inputDataPrimeiraParcela.value = '';
                campoData.classList.remove('hidden');
                campoDataInput.required = true;
            } else {
                inputParcelas.readOnly = false;
                areaParcelas.classList.add('hidden');
                parcelasHidden.innerHTML = '';
                listaParcelas.innerHTML = '';
                inputValorParcelaPreview.value = '';
                inputDataPrimeiraParcela.required = false;
                inputDataPrimeiraParcela.value = '';
                campoData.classList.add('hidden');
                campoDataInput.required = false;
                campoDataInput.value = '';
            }
        }

        function toggleMsg(element, condition) {
            element.classList.toggle('hidden', condition);
        }

        function bloquearCampos() {
            valor.disabled = !tipoPagamento.value;
            toggleMsg(msgValor, tipoPagamento.value);
            prazo.disabled = !valor.value;
            toggleMsg(msgPrazo, valor.value);
            parcelas.disabled = !prazo.value;
            toggleMsg(msgParcelas, prazo.value);

            const qtd = parseInt(parcelas.value) || 0;
            const usaParcelas = prazo.value === 'Parcelado' || (prazo.value === 'Entrada' && qtd > 1);
            const usaDataUnica = prazo.value === 'À vista' || (prazo.value === 'Entrada' && qtd <= 1);

            if (usaParcelas) {
                campoData.classList.add('hidden');
                dataPagamento.disabled = true;
                areaParcelas.classList.remove('hidden');
            } else if (usaDataUnica) {
                campoData.classList.remove('hidden');
                dataPagamento.disabled = !parcelas.value;
                areaParcelas.classList.add('hidden');
            } else {
                campoData.classList.add('hidden');
                dataPagamento.disabled = true;
                areaParcelas.classList.add('hidden');
            }

            descricao.disabled = usaDataUnica ? !dataPagamento.value : !parcelas.value;
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!validarValorMaximo() || !validarParcelas() || btnSalvar.disabled) return;

            abrirModal('modalSalvarFormaPagamento', function() {
                btnSalvar.disabled = true;
                textoSalvar.innerText = 'Salvando...';
                btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
                form.submit();
            });
        });

        tipoPagamento.addEventListener('change', bloquearCampos);
        inputIntervaloDias.addEventListener('change', gerarParcelas);

        inputValorTotal.addEventListener('input', function() {
            formatarMoedaBR(this);
            bloquearCampos();
            validarValorMaximo();
            gerarParcelas();
        });

        selectPrazo.addEventListener('change', function() {
            controlarCampos();
            bloquearCampos();
        });

        inputParcelas.addEventListener('input', function() {
            validarParcelas();
            controlarCampos();
            bloquearCampos();
            gerarParcelas();
        });

        inputDataPrimeiraParcela.addEventListener('change', gerarParcelas);
        dataPagamento.addEventListener('change', bloquearCampos);

        valor.disabled = true;
        prazo.disabled = true;
        parcelas.disabled = true;
        dataPagamento.disabled = true;
        descricao.disabled = true;

        controlarCampos();
        bloquearCampos();
    });
</script>
@endpush

@endsection