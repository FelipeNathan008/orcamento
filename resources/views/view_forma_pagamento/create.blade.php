@extends('layouts.app_financeiro')

@section('title', 'Cadastrar Nova Forma de Pagamento')

@section('content')
@php
$financeiroId = request('financeiro_id') ?? array_key_first(request()->query());

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

<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">
    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Cadastro de Nova Forma de Pagamento</h1>

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <strong>Erros encontrados:</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="formaPagamentoForm" action="{{ route('forma_pagamento.store') }}" method="POST" class="space-y-6"
        data-valor-faltante="{{ $valorFaltante }}"> @csrf

        <input type="hidden" name="financeiro_id_fin" value="{{ $financeiroId }}">


        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($financeiroSelecionado)
            <div class="md:col-span-2 bg-orange-50 border border-orange-200 rounded-lg p-6 shadow-sm">
                <h2 class="text-lg font-bold text-orange-700 mb-4">Informações do Financeiro</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
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
                        <p class="font-semibold text-gray-900">{{ $financeiroSelecionado->fin_nome_cliente }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600">Status</p>
                        <p class="font-semibold text-gray-900">{{ $financeiroSelecionado->fin_status }}</p>
                    </div>
                </div>
            </div>
            @endif
            <div id="valorTotalPedido" class="md:col-span-2 mt-2">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-sm font-semibold text-gray-700">
                            Resumo financeiro
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-gray-200">

                        {{-- Valor Total --}}
                        <div class="px-5 py-4">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                                Valor Total
                            </p>

                            <p class="mt-1 text-lg font-semibold text-gray-800">
                                R$ {{ number_format($valorTotal, 2, ',', '.') }}
                            </p>
                        </div>

                        {{-- Valor Pago --}}
                        <div class="px-5 py-4">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                                Valor Pago
                            </p>

                            <p class="mt-1 text-lg font-semibold text-gray-800">
                                R$ {{ number_format($valorPago, 2, ',', '.') }}
                            </p>
                        </div>

                        {{-- Valor Faltante --}}
                        <div class="px-5 py-4 bg-red-50">
                            <p class="text-xs font-semibold text-red-600 uppercase tracking-wide">
                                Valor Faltante
                            </p>

                            <p class="mt-1 text-xl font-bold text-red-700">
                                R$ {{ number_format($valorFaltante, 2, ',', '.') }}
                            </p>

                            <p class="mt-1 text-xs text-red-500">
                                O pagamento não pode ultrapassar este valor.
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <div class="md:col-span-1">
                <label for="tipo_pagamento_id_tipo" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Tipo de Pagamento
                </label>
                <select name="tipo_pagamento_id_tipo" id="tipo_pagamento_id_tipo" required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                    <option value="">Selecione</option>
                    @foreach ($tiposPagamento as $tipo)
                    <option value="{{ $tipo->id_tipo_pagamento }}" {{ old('tipo_pagamento_id_tipo') == $tipo->id_tipo_pagamento ? 'selected' : '' }}>
                        {{ $tipo->tipo_plano_fin }}
                    </option>
                    @endforeach
                </select>
                @error('tipo_pagamento_id_tipo')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-1">
                <label for="forma_valor" class="block text-sm font-medium text-custom-dark-text mb-1">Valor Total</label>
                <input type="text" name="forma_valor" id="forma_valor"
                    value="{{ old('forma_valor') }}" required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                    placeholder="R$ 0,00">
                <p id="msg_valor" class="text-xs text-gray-500 hidden">Preencha o tipo de pagamento primeiro</p>
                <p id="msg_valor_maximo" class="text-xs text-red-600 hidden mt-1"></p>
                @error('forma_valor')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-1">
                <label for="forma_mes" class="block text-sm font-medium text-custom-dark-text mb-1">Competência (Mês)</label>
                <input type="number" name="forma_mes" id="forma_mes"
                    value="{{ old('forma_mes') }}"
                    min="1"
                    max="12"
                    required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                    placeholder="1 até 12">
                @error('forma_mes')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-1">
                <label for="forma_prazo" class="block text-sm font-medium text-custom-dark-text mb-1">Prazo</label>
                <select name="forma_prazo" id="forma_prazo" required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                    <option value="">Selecione...</option>
                    <option value="Entrada" {{ old('forma_prazo') == 'Entrada' ? 'selected' : '' }}>Entrada</option>
                    <option value="À vista" {{ old('forma_prazo') == 'À vista' ? 'selected' : '' }}>À vista</option>
                    <option value="Parcelado" {{ old('forma_prazo') == 'Parcelado' ? 'selected' : '' }}>Parcelado</option>
                </select>
                <p id="msg_prazo" class="text-xs text-gray-500 hidden">Preencha o valor primeiro</p>

                @error('forma_prazo')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-custom-dark-text mb-1">Conta Bancária</label>
                <select name="conta_bancaria_id" required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                    <option value="">Selecione</option>
                    @foreach ($contas as $conta)
                    <option value="{{ $conta->id_conta }}">
                        {{ $conta->conta_nome_banco }} - {{ $conta->numero_conta_corrente }}
                    </option>
                    @endforeach
                </select>
                @error('conta_bancaria_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-1">
                <label for="forma_qtd_parcela" class="block text-sm font-medium text-custom-dark-text mb-1">Qtd Parcelas</label>
                <input type="number" name="forma_qtd_parcela" id="forma_qtd_parcela"
                    value="{{ old('forma_qtd_parcela', 1) }}"
                    min="1"
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                    placeholder="1, 2, 3...">
                <p id="msg_parcelas" class="text-xs text-gray-500 hidden">Selecione o prazo primeiro</p>

                @error('forma_qtd_parcela')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div id="areaParcelas" class="hidden md:col-span-2 bg-gray-50 border border-gray-200 rounded-lg p-5">
                <h2 class="text-lg font-bold text-gray-700 mb-4">Parcelas geradas</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="data_primeira_parcela" class="block text-sm font-medium text-custom-dark-text mb-1">
                            Data da 1ª parcela
                        </label>
                        <input type="date" id="data_primeira_parcela"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-custom-dark-text mb-1">
                            Valor de cada parcela
                        </label>
                        <input type="text" id="valor_parcela_preview"
                            class="block w-full px-4 py-2 bg-gray-100 text-gray-900 rounded-md border border-gray-300"
                            readonly>
                    </div>
                </div>

                <div id="listaParcelas" class="space-y-2"></div>
                <div id="parcelasHidden"></div>
            </div>

            <div class="md:col-span-1 hidden" id="campo_data">
                <label class="block text-sm font-medium text-custom-dark-text mb-1">Data do Pagamento</label>
                <input type="date" name="forma_data" id="forma_data"
                    value="{{ old('forma_data') }}"
                    max="{{ date('Y-m-d') }}"
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                @error('forma_data')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="forma_descricao" class="block text-sm font-medium text-custom-dark-text mb-1">Descrição</label>
                <textarea name="forma_descricao" id="forma_descricao" placeholder="Entrada, Chegada do Material..." required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">{{ old('forma_descricao') }}</textarea>
                @error('forma_descricao')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>


        <div class="flex justify-center mt-8">
            <button type="submit" id="btnSalvarForma"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-button-save-bg hover:bg-button-save-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                SALVAR
            </button>
        </div>

        <div class="flex justify-center mb-8">
            <a href="{{ url('/forma_pagamento?' . $financeiroId) }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR PARA A LISTA
            </a>
        </div>
    </form>
</div>

<script>
    // ===== Referências de elementos =====
    const form = document.getElementById('formaPagamentoForm');
    const btnSalvar = document.getElementById('btnSalvarForma');
    const valorFaltante = parseFloat(form.dataset.valorFaltante || '0');

    const msgValorMaximo = document.getElementById('msg_valor_maximo');

    const selectPrazo = document.getElementById('forma_prazo');
    const inputParcelas = document.getElementById('forma_qtd_parcela');
    const areaParcelas = document.getElementById('areaParcelas');
    const inputDataPrimeiraParcela = document.getElementById('data_primeira_parcela');
    const listaParcelas = document.getElementById('listaParcelas');
    const parcelasHidden = document.getElementById('parcelasHidden');
    const inputValorTotal = document.getElementById('forma_valor'); // = "valor"
    const inputValorParcelaPreview = document.getElementById('valor_parcela_preview');
    const campoData = document.getElementById('campo_data');
    const campoDataInput = document.getElementById('forma_data');

    const tipoPagamento = document.getElementById('tipo_pagamento_id_tipo');
    const valor = inputValorTotal; // mesmo elemento, evita pegar 2x pelo id
    const prazo = selectPrazo;
    const parcelas = inputParcelas;
    const dataPagamento = campoDataInput;
    const descricao = document.getElementById('forma_descricao');

    const msgValor = document.getElementById('msg_valor');
    const msgPrazo = document.getElementById('msg_prazo');
    const msgParcelas = document.getElementById('msg_parcelas');

    // ===== Funções auxiliares =====
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
        v = (parseInt(v) / 100).toFixed(2) + '';
        v = v.replace('.', ',');
        v = v.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
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
        } else {
            msgValorMaximo.classList.add('hidden');
            inputValorTotal.setCustomValidity('');
            btnSalvar.disabled = false;
            btnSalvar.classList.remove('opacity-50', 'cursor-not-allowed');
            return true;
        }
    }

    function calcularValorParcela() {
        const total = converterValorBRparaFloat(inputValorTotal.value);
        const qtd = parseInt(inputParcelas.value);
        if (!total || !qtd || qtd <= 0) return 0;
        return Number((total / qtd).toFixed(2));
    }

    function add30dias(data) {
        const nova = new Date(data);
        nova.setDate(nova.getDate() + 30);
        return nova;
    }

    function gerarParcelas() {
        listaParcelas.innerHTML = '';
        parcelasHidden.innerHTML = '';

        const qtd = parseInt(inputParcelas.value);
        const dataInicial = inputDataPrimeiraParcela.value;
        const valorParcela = calcularValorParcela();

        if (!qtd || !dataInicial || !valorParcela) {
            inputValorParcelaPreview.value = '';
            return;
        }

        inputValorParcelaPreview.value = 'R$ ' + formatarBR(valorParcela);

        let data = new Date(dataInicial + 'T00:00:00');

        for (let i = 1; i <= qtd; i++) {
            const dia = String(data.getDate()).padStart(2, '0');
            const mes = String(data.getMonth() + 1).padStart(2, '0');
            const ano = data.getFullYear();

            listaParcelas.innerHTML += `
                <div class="p-3 bg-white rounded border border-gray-300 flex justify-between">
                    <span>Parcela ${i}</span>
                    <span><strong>${dia}/${mes}/${ano}</strong> - R$ ${formatarBR(valorParcela)}</span>
                </div>
            `;

            parcelasHidden.innerHTML += `
                <input type="hidden" name="datas_parcelas[]" value="${ano}-${mes}-${dia}">
                <input type="hidden" name="valores_parcelas[]" value="${valorParcela}">
            `;

            data = add30dias(data);
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
            // só trava em 1 quando for exatamente "À vista"
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
            // nenhum prazo selecionado
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

    // ===== Eventos =====
    form.addEventListener('submit', function(e) {
        if (!validarValorMaximo()) {
            e.preventDefault();
            return false;
        }
        if (btnSalvar.disabled) {
            return false;
        }
        btnSalvar.disabled = true;
        btnSalvar.innerText = 'SALVANDO...';
        btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
    });

    tipoPagamento.addEventListener('change', bloquearCampos); // <-- FALTAVA ISSO

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
        controlarCampos();
        bloquearCampos();
    });

    inputDataPrimeiraParcela.addEventListener('change', gerarParcelas);
    dataPagamento.addEventListener('change', bloquearCampos);

    window.addEventListener('DOMContentLoaded', () => {
        // começa tudo travado
        valor.disabled = true;
        prazo.disabled = true;
        parcelas.disabled = true;
        dataPagamento.disabled = true;
        descricao.disabled = true;

        controlarCampos();
        bloquearCampos();
    });
</script>
@endsection