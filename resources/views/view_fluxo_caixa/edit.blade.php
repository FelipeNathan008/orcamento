@extends('layouts.app')

@section('title', 'Editar Fluxo de Caixa')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Editar Lançamento de Fluxo de Caixa
    </h1>

    {{-- ERROS --}}
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <strong>Opa!</strong>
        <ul class="mt-3 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
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

                        <option value="">Selecione</option>

                        @foreach ($tipos as $tipo)
                        <option value="{{ $tipo->id_tipo_fluxo }}"
                            data-despesa="{{ $tipo->tipo_despesa }}"
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

                        <option value="">Selecione</option>

                        @foreach ($movimentacoes as $mov)
                        <option value="{{ $mov->id_movimentacao }}"
                            {{ $fluxo->flu_id_movimentacao == $mov->id_movimentacao ? 'selected' : '' }}>
                            {{ $mov->mov_nome }}
                        </option>
                        @endforeach

                    </select>
                </div>

            </div>

            {{-- DIREITA --}}
            <div class="space-y-6">

                {{-- VALOR --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Valor</label>

                    <input type="text" id="valorMask"
                        class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                        required>

                    <input type="hidden" name="flu_valor" id="valorReal"
                        value="{{ old('flu_valor', $fluxo->flu_valor) }}">
                </div>

                {{-- DOC --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Num. Documento</label>
                    <input type="text" name="flu_num_doc"
                        value="{{ old('flu_num_doc', $fluxo->flu_num_doc) }}"
                        class="w-full px-4 py-2 border rounded-md">
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
        const options = Array.from(selectTipo.options);

        // FILTRO AUTOMÁTICO AO CARREGAR
        function filtrar() {
            const valor = filtro.value;

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
        }

        filtro.addEventListener('change', filtrar);
        filtrar(); // executa ao carregar

        // MÁSCARA VALOR
        const valorInput = document.getElementById('valorMask');
        const valorReal = document.getElementById('valorReal');

        // carregar valor inicial formatado
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

    });
</script>
@endpush