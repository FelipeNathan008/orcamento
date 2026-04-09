@extends('layouts.app')

@section('title', 'Criar Fluxo de Caixa')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Novo Lançamento de Fluxo de Caixa
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

    <form id="formFluxo" action="{{ route('fluxo_caixa.store') }}" method="POST" class="space-y-6">
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
                            data-despesa="{{ $tipo->tipo_despesa }}">
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
                        <option value="{{ $mov->id_movimentacao }}">
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
                        placeholder="R$ 0,00"
                        required>

                    {{-- valor real escondido --}}
                    <input type="hidden" name="flu_valor" id="valorReal">
                </div>

                {{-- DOC --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Num. Documento (Opcional)</label>
                    <input type="text" name="flu_num_doc" placeholder="Opcional"
                        class="w-full px-4 py-2 border rounded-md">
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
            <button class="px-8 py-3 text-white rounded-md bg-button-save-bg">
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
    document.addEventListener('DOMContentLoaded', function() {

        // DATA AUTOMÁTICA
        const hoje = new Date().toISOString().split('T')[0];
        document.getElementById('dataHoje').value = hoje;

        // FILTRO FIXO/VARIAVEL
        const filtro = document.getElementById('filtroDespesa');
        const selectTipo = document.getElementById('selectTipo');
        const options = Array.from(selectTipo.options);

        // começa desabilitado
        selectTipo.disabled = true;

        filtro.addEventListener('change', function() {

            const valor = this.value;

            // limpa opções
            selectTipo.innerHTML = '<option value="">Selecione</option>';

            if (!valor) {
                selectTipo.disabled = true;
                return;
            }

            // habilita select
            selectTipo.disabled = false;

            options.forEach(opt => {
                if (!opt.value) return;

                if (opt.dataset.despesa === valor) {
                    selectTipo.appendChild(opt);
                }
            });

        });

        // 🔹 MÁSCARA DE DINHEIRO
        const valorInput = document.getElementById('valorMask');
        const valorReal = document.getElementById('valorReal');

        valorInput.addEventListener('input', function() {

            let value = this.value.replace(/\D/g, '');

            value = (value / 100).toFixed(2) + '';

            value = value.replace('.', ',');

            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            this.value = 'R$ ' + value;

            // salva sem máscara
            valorReal.value = value.replace(/\./g, '').replace(',', '.');
        });

        // REMOVE MÁSCARA AO ENVIAR
        document.getElementById('formFluxo').addEventListener('submit', function() {
            valorReal.value = value.replace(/\./g, '').replace(',', '.');
        });

    });
</script>
@endpush