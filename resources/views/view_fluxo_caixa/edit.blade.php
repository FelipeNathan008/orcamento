@extends('layouts.app')

@section('title', 'Editar Fluxo de Caixa')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Editar Lançamento de Fluxo de Caixa
    </h1>


    {{-- ERROS --}}
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4">
        {{ session('error') }}
    </div>
    @endif

    @if(isset($fluxo))

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
    @endif

    <form id="formFluxo" action="{{ route('fluxo_caixa.update', $fluxo->id_fluxo) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

            {{-- ESQUERDA --}}
            <div class="space-y-6">

                {{-- DATA --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Data</label>
                    <input type="date" name="flu_data_despesa" id="dataHoje"
                        class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                        value="{{ old('flu_data_despesa', $fluxo->flu_data_despesa) }}"
                        required>
                </div>

                {{-- DESPESA --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Tipo de Despesa</label>

                    <select id="filtroDespesa"
                        class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                        required>

                        <option value="">Selecione</option>
                        <option value="Fixa"
                            {{ $fluxo->tipo->tipo_despesa == 'Fixa' ? 'selected' : '' }}>
                            Fixa
                        </option>
                        <option value="Variavel"
                            {{ $fluxo->tipo->tipo_despesa == 'Variavel' ? 'selected' : '' }}>
                            Variável
                        </option>

                    </select>
                </div>

                {{-- TIPO --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Tipo</label>

                    <select name="flu_id_tipo" id="selectTipo"
                        class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                        required>

                        @foreach ($tipos as $tipo)
                        <option value="{{ $tipo->id_tipo_fluxo }}"
                            data-despesa="{{ $tipo->tipo_despesa }}"
                            data-caixa="{{ str_contains(strtolower($tipo->tipo_flu_nome), 'caixa') ? '1' : '0' }}"
                            {{ $fluxo->flu_id_tipo == $tipo->id_tipo_fluxo ? 'selected' : '' }}>
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


                        @foreach ($movimentacoes as $mov)
                        <option value="{{ $mov->id_movimentacao }}"
                            data-tipo="{{ $mov->mov_nome }}"
                            {{ $fluxo->flu_id_movimentacao == $mov->id_movimentacao ? 'selected' : '' }}>
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
                        <option value="{{ $conta->id_conta }}"
                            {{ $fluxo->conta_bancaria_id == $conta->id_conta ? 'selected' : '' }}>
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
                        required>

                    <input type="hidden" name="flu_valor" id="valorReal"
                        value="{{ old('flu_valor', $fluxo->flu_valor) }}">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- TIPO FISCAL --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Tipo Fiscal</label>

                        <select name="flu_tipo_fiscal" id="tipoFiscal"
                            class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                            required>

                            <option value="">Selecione</option>
                            <option value="NF" {{ $fluxo->flu_tipo_fiscal == 'NF' ? 'selected' : '' }}>Nota Fiscal (NF)</option>
                            <option value="RC" {{ $fluxo->flu_tipo_fiscal == 'RC' ? 'selected' : '' }}>Recibo (RC)</option>
                            <option value="CF" {{ $fluxo->flu_tipo_fiscal == 'CF' ? 'selected' : '' }}>Cupom Fiscal (CF)</option>
                            <option value="OUT" {{ $fluxo->flu_tipo_fiscal == 'OUT' ? 'selected' : '' }}>Outros</option>
                        </select>
                    </div>

                    {{-- DOC --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Num. Documento</label>
                        <input type="text" name="flu_num_doc" id="numDoc" placeholder="Opcional"
                            value="{{ old('flu_num_doc', $fluxo->flu_num_doc) }}"
                            class="w-full px-4 py-2 border rounded-md">
                    </div>
                </div>

                {{-- DESC --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Descrição</label>
                    <textarea name="flu_desc"
                        rows="4"
                        class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                        maxlength="180"
                        required>{{ old('flu_desc', $fluxo->flu_desc) }}</textarea>
                </div>

            </div>

        </div>

        <div class="flex justify-center mt-8">
            <button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                ATUALIZAR
            </button>
        </div>

        {{-- Botão Voltar unificado e movido para fora do formulário --}}
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
    document.addEventListener('DOMContentLoaded', function() {

        const filtro = document.getElementById('filtroDespesa');
        const selectTipo = document.getElementById('selectTipo');
        const movimentacao = document.querySelector('[name="flu_id_movimentacao"]');
        const tipoFiscal = document.getElementById('tipoFiscal');
        const numDoc = document.getElementById('numDoc');

        const optionsTipo = Array.from(selectTipo.options);
        const optionsMov = Array.from(movimentacao.options);

        const valorMovimentacaoInicial = movimentacao.value;
        const valorTipoFiscalInicial = tipoFiscal.value;

        function normalizarTexto(texto) {
            return texto
                .toLowerCase()
                .normalize("NFD")
                .replace(/[\u0300-\u036f]/g, "");
        }

        function filtrarTipo() {
            const valor = filtro.value;
            selectTipo.innerHTML = '<option value="">Selecione</option>';

            if (!valor) {
                selectTipo.disabled = true;
                return;
            }

            selectTipo.disabled = false;

            optionsTipo.forEach(opt => {
                if (!opt.value) return;
                if (opt.dataset.despesa === valor) {
                    selectTipo.appendChild(opt);
                }
            });
        }

        function atualizarMovimentacao() {
            const selectedTipo = selectTipo.options[selectTipo.selectedIndex];

            if (!selectedTipo || !selectedTipo.value) {
                movimentacao.innerHTML = '<option value="">Selecione</option>';
                movimentacao.disabled = true;
                return;
            }

            const isCaixa = selectedTipo.dataset.caixa === '1';
            const movAtual = valorMovimentacaoInicial;

            movimentacao.innerHTML = '<option value="">Selecione</option>';
            movimentacao.disabled = false;

            optionsMov.forEach(opt => {
                if (!opt.value) return;

                let nome = normalizarTexto(opt.dataset.tipo);

                if (isCaixa) {
                    if (nome.includes('caixa')) {
                        movimentacao.appendChild(opt);
                    }
                } else {
                    if (!nome.includes('caixa')) {
                        movimentacao.appendChild(opt);
                    }
                }
            });

            movimentacao.value = movAtual;
        }

        function atualizarTipoFiscal() {
            const selected = movimentacao.options[movimentacao.selectedIndex];

            if (!selected || !selected.dataset.tipo) {
                return;
            }

            const valorAtual = valorTipoFiscalInicial;
            let tipo = normalizarTexto(selected.dataset.tipo);

            tipoFiscal.innerHTML = '<option value="">Selecione</option>';

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

            tipoFiscal.value = valorAtual;
        }

        filtro.addEventListener('change', filtrarTipo);
        selectTipo.addEventListener('change', atualizarMovimentacao);
        movimentacao.addEventListener('change', atualizarTipoFiscal);

        tipoFiscal.addEventListener('change', function() {
            if (this.value) {
                numDoc.disabled = false;
            } else {
                numDoc.disabled = true;
                numDoc.value = '';
            }
        });

        const valorInput = document.getElementById('valorMask');
        const valorReal = document.getElementById('valorReal');

        if (valorReal.value) {
            let value = parseFloat(valorReal.value).toFixed(2).replace('.', ',');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            valorInput.value = 'R$ ' + value;
        }

        valorInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            value = (value / 100).toFixed(2) + '';
            value = value.replace('.', ',');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            this.value = 'R$ ' + value;
            valorReal.value = value.replace(/\./g, '').replace(',', '.');
        });

        filtrarTipo();
        setTimeout(() => {
            atualizarMovimentacao();
            atualizarTipoFiscal();
        }, 100);

    });
</script>
@endpush