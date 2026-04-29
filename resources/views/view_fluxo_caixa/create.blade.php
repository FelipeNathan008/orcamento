@extends('layouts.app')

@section('title', 'Criar Fluxo de Caixa')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Novo Lançamento de Fluxo de Caixa
    </h1>

    {{-- ERROS --}}
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações da caixa
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

            <div>
                <p class="text-gray-600">Saldo Caixa Total</p>
                <p class="font-semibold text-gray-900">
                    R$ {{ number_format($saldoTotal, 2, ',', '.') }}
                </p>
            </div>

        </div>

    </div>


    <form id="fluxoCaixaForm" action="{{ route('fluxo_caixa.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

            {{-- ESQUERDA --}}
            <div class="space-y-6">

                {{-- DATA AUTO --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Data</label>
                    <input type="date" name="flu_data_despesa" id="dataHoje"
                        class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                        required>
                </div>

                {{-- DESPESA --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Tipo de Despesa</label>

                    <select id="filtroDespesa"
                        class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                        required>

                        <option value="">Selecione</option>
                        <option value="Fixa">Fixa</option>
                        <option value="Variavel">Variável</option>

                    </select>
                </div>

                {{-- TIPO --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Tipo</label>

                    <select name="flu_id_tipo" id="selectTipo"
                        class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                        required>

                        <option value="">Selecione</option>

                        @foreach ($tipos as $tipo)
                        <option value="{{ $tipo->id_tipo_fluxo }}"
                            data-despesa="{{ $tipo->tipo_despesa }}"
                            data-caixa="{{ str_contains(strtolower($tipo->tipo_flu_nome), 'caixa') ? '1' : '0' }}">
                            {{ $tipo->tipo_flu_nome }}
                        </option>
                        @endforeach

                    </select>
                </div>

                {{-- MOVIMENTAÇÃO --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Movimentação</label>

                    <select name="flu_id_movimentacao"
                        class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                        required>

                        <option value="">Selecione</option>

                        @foreach ($movimentacoes as $mov)
                        <option value="{{ $mov->id_movimentacao }}"
                            data-tipo="{{ $mov->mov_nome }}">
                            {{ $mov->mov_nome }}
                        </option>
                        @endforeach

                    </select>
                </div>

            </div>

            {{-- DIREITA --}}
            <div class="space-y-6">

                {{-- CONTA BANCÁRIA --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Conta Bancária</label>

                    <select name="conta_bancaria_id"
                        class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500">

                        <option value="">Selecione</option>

                        @foreach ($contas as $conta)
                        <option value="{{ $conta->id_conta }}">
                            {{ $conta->conta_nome_banco }} -
                            {{ $conta->numero_conta_corrente }}
                        </option>
                        @endforeach

                    </select>
                </div>

                {{-- VALOR --}}
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
                        <div>

                            <select name="flu_tipo_fiscal" id="tipoFiscal"
                                class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                                required>

                                <option value="">Selecione</option>
                            </select>
                        </div>
                    </div>

                    {{-- DOC --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Num. Documento (Opcional)</label>
                        <input type="text" name="flu_num_doc" id="numDoc" placeholder="Opcional"
                            class="w-full px-4 py-2 border rounded-md"
                            disabled>
                    </div>

                </div>

                {{-- DESC --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Descrição</label>
                    <textarea name="flu_desc"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500"
                        maxlength="180"
                        required>{{ old('flu_desc') }}</textarea>
                </div>

            </div>

        </div>

        <div class="flex justify-center mt-8">
            <button id="btnSalvarFluxoCaixa" class="px-8 py-3 text-white rounded-md bg-button-save-bg">
                SALVAR
            </button>
        </div>
        <div class="flex justify-center mb-8">
            <a href="{{ route('fluxo_caixa.index') }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR PARA A LISTA
            </a>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
    const form = document.getElementById('fluxoCaixaForm');
    const btnSalvar = document.getElementById('btnSalvarFluxoCaixa');

    form.addEventListener('submit', function() {

        if (btnSalvar.disabled) {
            return false;
        }
        btnSalvar.disabled = true;
        btnSalvar.innerText = 'SALVANDO...';
        btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
    });
    document.addEventListener('DOMContentLoaded', function() {

        // =========================
        // DATA AUTOMÁTICA
        // =========================
        const hoje = new Date().toISOString().split('T')[0];
        document.getElementById('dataHoje').value = hoje;

        // =========================
        // FILTRO TIPO (Fixa/Variável)
        // =========================
        const filtro = document.getElementById('filtroDespesa');
        const selectTipo = document.getElementById('selectTipo');
        const options = Array.from(selectTipo.options);

        selectTipo.disabled = true;

        filtro.addEventListener('change', function() {

            const valor = this.value;

            selectTipo.innerHTML = '<option value="">Selecione</option>';

            if (!valor) {
                selectTipo.disabled = true;
                return;
            }

            selectTipo.disabled = false;

            options.forEach(opt => {
                if (!opt.value) return;

                if (opt.dataset.despesa === valor) {
                    selectTipo.appendChild(opt);
                }
            });
        });

        // =========================
        // VARIÁVEIS TIPO FISCAL
        // =========================
        const movimentacao = document.querySelector('[name="flu_id_movimentacao"]');

        const movOptions = Array.from(movimentacao.options);
        movimentacao.disabled = true;

        const tipoFiscal = document.getElementById('tipoFiscal');
        const numDoc = document.getElementById('numDoc');

        // inicia bloqueado (igual você queria)
        tipoFiscal.disabled = true;
        numDoc.disabled = true;

        function atualizarMovimentacao() {

            const selectedTipo = selectTipo.options[selectTipo.selectedIndex];

            if (!selectedTipo || !selectedTipo.value) {
                movimentacao.innerHTML = '<option value="">Selecione</option>';
                movimentacao.disabled = true;
                return;
            }

            const isCaixa = selectedTipo.dataset.caixa === '1';

            movimentacao.innerHTML = '<option value="">Selecione</option>';
            movimentacao.disabled = false;

            movOptions.forEach(opt => {

                if (!opt.value) return;

                let nome = normalizarTexto(opt.dataset.tipo);

                if (isCaixa) {
                    // só mostra os que tem "caixa"
                    if (nome.includes('caixa')) {
                        movimentacao.appendChild(opt);
                    }
                } else {
                    // mostra tudo MENOS caixa
                    if (!nome.includes('caixa')) {
                        movimentacao.appendChild(opt);
                    }
                }
            });

            // reset dependentes
            tipoFiscal.innerHTML = '<option value="">Selecione</option>';
            tipoFiscal.disabled = true;
            numDoc.disabled = true;
            numDoc.value = '';
        }
        selectTipo.addEventListener('change', atualizarMovimentacao);
        // =========================
        // NORMALIZAR TEXTO (remove acento)
        // =========================
        function normalizarTexto(texto) {
            return texto
                .toLowerCase()
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "");
        }



        // =========================
        // ATUALIZAR TIPO FISCAL
        // =========================
        function atualizarTipoFiscal() {

            const selected = movimentacao.options[movimentacao.selectedIndex];

            if (!selected || !selected.dataset.tipo) {
                tipoFiscal.disabled = true;
                tipoFiscal.innerHTML = '<option value="">Selecione</option>';
                return;
            }

            let tipo = normalizarTexto(selected.dataset.tipo);

            tipoFiscal.innerHTML = '<option value="">Selecione</option>';
            tipoFiscal.disabled = true;

            if (tipo.includes('saida')) {

                tipoFiscal.innerHTML += `
                <option value="NF">Nota Fiscal (NF)</option>
                <option value="RC">Recibo (RC)</option>
                <option value="CF">Cupom Fiscal (CF)</option>
                <option value="OUT">Outros</option>
            `;

                tipoFiscal.disabled = false;

            } else if (tipo.includes('entrada')) {

                tipoFiscal.innerHTML += `
                <option value="OC">Orçamento (OC)</option>
            `;

                tipoFiscal.disabled = false;
            }

            // reset documento
            numDoc.value = '';
            numDoc.disabled = true;
        }

        // =========================
        // EVENTOS
        // =========================
        movimentacao.addEventListener('change', atualizarTipoFiscal);

        tipoFiscal.addEventListener('change', function() {
            if (this.value) {
                numDoc.disabled = false;
            } else {
                numDoc.disabled = true;
                numDoc.value = '';
            }
        });

        // =========================
        // MÁSCARA DE DINHEIRO
        // =========================
        const valorInput = document.getElementById('valorMask');
        const valorReal = document.getElementById('valorReal');

        valorInput.addEventListener('input', function() {

            let value = this.value.replace(/\D/g, '');
            value = (value / 100).toFixed(2) + '';
            value = value.replace('.', ',');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            this.value = 'R$ ' + value;

            valorReal.value = value.replace(/\./g, '').replace(',', '.');
        });

        document.getElementById('fluxoCaixaForm').addEventListener('submit', function() {
            let value = valorInput.value.replace(/\D/g, '');
            value = (value / 100).toFixed(2);
            valorReal.value = value;
        });

    });
</script>
@endpush