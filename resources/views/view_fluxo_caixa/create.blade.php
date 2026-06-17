@extends('layouts.app')

@section('title', 'Novo Lançamento')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Novo Lançamento de Fluxo de Caixa
    </h1>

    <x-alert-flash />

    <form action="{{ route('fluxo_caixa.store') }}"
        method="POST"
        id="fluxoCaixaForm"
        class="space-y-6">

        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- COLUNA ESQUERDA --}}
            <div>

                {{-- DATA --}}
                <div>
                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Data
                    </label>

                    <input type="date"
                        name="flu_data_despesa"
                        value="{{ old('flu_data_despesa') }}"
                        required
                        class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md">

                    @error('flu_data_despesa')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CATEGORIA --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Categoria
                    </label>

                    <select
                        id="categoria_tipo"
                        name="categoria_tipo"
                        required
                        class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md">

                        <option value="">Selecione</option>
                        <option value="fixa"
                            {{ old('categoria_tipo') == 'fixa' ? 'selected' : '' }}>
                            Fixa
                        </option>

                        <option value="variavel"
                            {{ old('categoria_tipo') == 'variavel' ? 'selected' : '' }}>
                            Variável
                        </option>

                        <option value="caixa"
                            {{ old('categoria_tipo') == 'caixa' ? 'selected' : '' }}>
                            Caixa
                        </option>

                    </select>
                </div>

                {{-- TIPO --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Tipo
                    </label>

                    <select
                        name="flu_id_tipo"
                        id="flu_id_tipo"
                        required
                        class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md">

                        <option value="">Selecione</option>

                        @foreach($tipos as $tipo)
                        <option value="{{ $tipo->id_tipo_fluxo }}"
                            {{ old('flu_id_tipo') == $tipo->id_tipo_fluxo ? 'selected' : '' }}>
                            {{ $tipo->tipo_flu_nome }}
                        </option>
                        @endforeach

                    </select>

                    @error('flu_id_tipo')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- MOVIMENTAÇÃO --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Movimentação
                    </label>

                    <select
                        name="flu_id_movimentacao"
                        id="flu_id_movimentacao"
                        required
                        class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md">

                        <option value="">Selecione</option>

                        @foreach($movimentacoes as $mov)
                        <option value="{{ $mov->id_movimentacao }}"
                            {{ old('flu_id_movimentacao') == $mov->id_movimentacao ? 'selected' : '' }}>
                            {{ $mov->mov_nome }}
                        </option>
                        @endforeach

                    </select>

                    @error('flu_id_movimentacao')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- CONTA --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Conta Bancária
                    </label>

                    <select name="conta_bancaria_id"
                        required
                        class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md">

                        <option value="">Selecione</option>

                        @foreach($contas as $conta)
                        <option value="{{ $conta->id_conta }}"
                            {{ old('conta_bancaria_id') == $conta->id_conta ? 'selected' : '' }}>
                            {{ $conta->conta_nome_banco }}
                        </option>
                        @endforeach

                    </select>

                    @error('conta_bancaria_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- COLUNA DIREITA --}}
            <div>

                {{-- VALOR --}}
                <div>
                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Valor
                    </label>

                    <input
                        type="text"
                        id="valorMask"
                        value="{{ old('flu_valor') ? 'R$ ' . number_format(old('flu_valor'), 2, ',', '.') : '' }}"
                        placeholder="R$ 0,00"
                        required
                        class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md">

                    {{-- valor real enviado ao controller --}}
                    <input
                        type="hidden"
                        name="flu_valor"
                        id="valorReal"
                        value="{{ old('flu_valor') }}">

                    @error('flu_valor')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TIPO FISCAL --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Tipo Fiscal
                    </label>

                    <select
                        name="flu_tipo_fiscal"
                        id="flu_tipo_fiscal"
                        required
                        disabled
                        class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md">

                        <option value="">Selecione a movimentação primeiro</option>

                    </select>

                    @error('flu_tipo_fiscal')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DOCUMENTO --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Número do Documento (Opcional)
                    </label>

                    <input type="text"
                        name="flu_num_doc"
                        maxlength="255"
                        value="{{ old('flu_num_doc') }}"
                        placeholder="Número do documento"
                        class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md">

                    @error('flu_num_doc')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DESCRIÇÃO --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Descrição
                    </label>

                    <textarea
                        name="flu_desc"
                        rows="6"
                        maxlength="180"
                        required
                        class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md"
                        placeholder="Descrição da movimentação">{{ old('flu_desc') }}</textarea>

                    @error('flu_desc')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

        </div>

        {{-- BOTÃO SALVAR --}}
        <div class="flex justify-center mt-8">
            <button type="submit"
                id="btnSalvarFluxo"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-button-save-bg hover:bg-button-save-hover">

                SALVAR

            </button>
        </div>

        {{-- BOTÃO VOLTAR --}}
        <div class="flex justify-center mb-8">
            <a href="{{ route('fluxo_caixa.index') }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400">
                VOLTAR PARA A LISTA
            </a>
        </div>

    </form>

</div>
@endsection
@push('scripts')
<div
    id="tiposFluxoData"
    data-tipos='@json($tipos)'>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // DADOS

        const tiposFluxo = JSON.parse(
            document.getElementById('tiposFluxoData').dataset.tipos
        );

        const tipoFiscalOld = "{{ old('flu_tipo_fiscal') }}";
        const tipoOld = "{{ old('flu_id_tipo') }}";

        // ELEMENTOS

        const form = document.getElementById('fluxoCaixaForm');
        const btnSalvar = document.getElementById('btnSalvarFluxo');

        const categoriaTipo = document.getElementById('categoria_tipo');
        const selectTipo = document.getElementById('flu_id_tipo');

        const movimentacao = document.getElementById('flu_id_movimentacao');
        const tipoFiscal = document.getElementById('flu_tipo_fiscal');

        const valorInput = document.getElementById('valorMask');
        const valorReal = document.getElementById('valorReal');

        // BOTÃO SALVAR

        form.addEventListener('submit', function() {

            tipoFiscal.disabled = false;

            if (btnSalvar.disabled) {
                return false;
            }

            btnSalvar.disabled = true;
            btnSalvar.innerText = 'SALVANDO...';
        });

        // MÁSCARA MONETÁRIA

        valorInput.addEventListener('input', function() {

            let value = this.value.replace(/\D/g, '');

            if (!value) {
                this.value = '';
                valorReal.value = '';
                return;
            }

            value = (value / 100).toFixed(2);
            value = value.replace('.', ',');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            this.value = 'R$ ' + value;

            valorReal.value = value
                .replace(/\./g, '')
                .replace(',', '.');
        });

        if (valorReal.value) {

            let value = parseFloat(valorReal.value)
                .toFixed(2)
                .replace('.', ',');

            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            valorInput.value = 'R$ ' + value;
        }

        // CARREGA TIPO FISCAL

        function carregarTiposFiscais() {

            const textoSelecionado =
                movimentacao.options[movimentacao.selectedIndex]
                ?.text
                ?.toLowerCase() || '';

            tipoFiscal.innerHTML =
                '<option value="">Selecione</option>';

            tipoFiscal.disabled = true;

            if (
                textoSelecionado.includes('saida') ||
                textoSelecionado.includes('saída')
            ) {

                tipoFiscal.innerHTML += `
                <option value="DG">Despesa Geral (DG)</option>
                <option value="NF">Nota Fiscal (NF)</option>
                <option value="RC">Recibo (RC)</option>
                <option value="CF">Cupom Fiscal (CF)</option>
                <option value="SA">Saque (SA)</option>
                <option value="OUT">Outros</option>
            `;

                tipoFiscal.disabled = false;
            } else if (textoSelecionado.includes('entrada')) {

                tipoFiscal.innerHTML += `
                <option value="OC">Orçamento (OC)</option>
                <option value="AP">Aporte (AP)</option>
            `;

                tipoFiscal.disabled = false;
            }

            if (tipoFiscalOld) {
                tipoFiscal.value = tipoFiscalOld;
            }
        }

        // CARREGA TIPOS

        function carregarTipos() {

            const categoria = categoriaTipo.value;

            selectTipo.innerHTML =
                '<option value="">Selecione</option>';

            if (!categoria) {
                return;
            }

            // CAIXA
            if (categoria === 'caixa') {

                const tipoCaixa = tiposFluxo.find(tipo =>
                    tipo.tipo_flu_nome &&
                    tipo.tipo_flu_nome.toLowerCase() === 'caixa'
                );

                if (tipoCaixa) {

                    selectTipo.innerHTML = `
                    <option value="${tipoCaixa.id_tipo_fluxo}" selected>
                        ${tipoCaixa.tipo_flu_nome}
                    </option>
                `;

                    selectTipo.value = tipoCaixa.id_tipo_fluxo;
                }

                return;
            }

            // FIXA E VARIAVEL
            tiposFluxo.forEach(tipo => {

                if (
                    tipo.tipo_despesa &&
                    tipo.tipo_despesa.toLowerCase() === categoria
                ) {

                    selectTipo.innerHTML += `
                    <option
                        value="${tipo.id_tipo_fluxo}"
                        ${String(tipo.id_tipo_fluxo) === String(tipoOld) ? 'selected' : ''}>
                        ${tipo.tipo_flu_nome}
                    </option>
                `;
                }
            });
        }

        // EVENTOS
        movimentacao.addEventListener(
            'change',
            carregarTiposFiscais
        );

        categoriaTipo.addEventListener(
            'change',
            carregarTipos
        );

        // INICIALIZAÇÃO
        carregarTipos();
        carregarTiposFiscais();

    });
</script>
@endpush