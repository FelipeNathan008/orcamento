@extends('layouts.app')

@section('title', 'Editar Nota Fiscal')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Editar Nota Fiscal
    </h1>

    {{-- ERROS --}}
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4">
        {{ session('error') }}
    </div>
    @endif

    <form id="notaForm" action="{{ route('nota_fiscal.update', $nota->id_nota_fiscal) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- ESQUERDA --}}
            <div class="space-y-6">

                {{-- ORÇAMENTO --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Orçamento</label>

                    <div class="flex gap-3">
                        <input type="text" id="orcamento_nome"
                            value="#{{ $nota->orcamento->id_orcamento }} - {{ $nota->orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}"
                            class="w-full px-4 py-2 border rounded-md bg-gray-100"
                            readonly>

                        <button type="button" id="btnBuscarOrcamento"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            🔎 Buscar
                        </button>
                    </div>

                    <input type="hidden" name="orcamento_id_orcamento"
                        id="orcamento_id"
                        value="{{ $nota->orcamento_id_orcamento }}">
                </div>

                {{-- DATA --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Data</label>
                    <input type="date" name="nota_data" id="dataHoje"
                        value="{{ old('nota_data', \Carbon\Carbon::parse($nota->nota_data)->format('Y-m-d')) }}"
                        class="w-full px-4 py-2 border rounded-md"
                        required>
                </div>

                {{-- NUMERO --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Número</label>
                    <input type="text" name="nota_numero"
                        value="{{ old('nota_numero', $nota->nota_numero) }}"
                        class="w-full px-4 py-2 border rounded-md"
                        required>
                </div>

            </div>

            {{-- DIREITA --}}
            <div class="space-y-6">

                {{-- DESPESA --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Tipo de Despesa</label>

                    <select id="filtroDespesa" class="w-full px-4 py-2 border rounded-md" required>
                        <option value="">Selecione</option>
                        <option value="Fixa" {{ $nota->tipo->tipo_despesa == 'Fixa' ? 'selected' : '' }}>Fixa</option>
                        <option value="Variavel" {{ $nota->tipo->tipo_despesa == 'Variavel' ? 'selected' : '' }}>Variável</option>
                    </select>
                </div>

                {{-- TIPO --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Tipo</label>

                    <select name="nota_id_tipo" id="selectTipo"
                        class="w-full px-4 py-2 border rounded-md" required>

                        @foreach ($tipos as $tipo)
                        <option value="{{ $tipo->id_tipo_fluxo }}"
                            data-despesa="{{ $tipo->tipo_despesa }}"
                            {{ $nota->nota_id_tipo == $tipo->id_tipo_fluxo ? 'selected' : '' }}>
                            {{ $tipo->tipo_flu_nome }}
                        </option>
                        @endforeach

                    </select>
                </div>

                {{-- MOVIMENTAÇÃO --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Movimentação</label>

                    <select name="nota_id_movimentacao" id="movimentacao"
                        class="w-full px-4 py-2 border rounded-md" required>

                        @foreach ($movimentacoes as $mov)
                        <option value="{{ $mov->id_movimentacao }}"
                            data-tipo="{{ $mov->mov_nome }}"
                            {{ $nota->nota_id_movimentacao == $mov->id_movimentacao ? 'selected' : '' }}>
                            {{ $mov->mov_nome }}
                        </option>
                        @endforeach

                    </select>
                </div>

            </div>

        </div>

        {{-- VALOR + DESC --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div>
                <label class="block text-sm font-medium mb-1">Valor</label>

                <input type="text" id="valorMask"
                    class="w-full px-4 py-2 border rounded-md"
                    required>

                <input type="hidden" name="nota_valor" id="valorReal"
                    value="{{ old('nota_valor', $nota->nota_valor) }}">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Descrição</label>
                <textarea name="nota_desc"
                    class="w-full px-4 py-2 border rounded-md"
                    required>{{ old('nota_desc', $nota->nota_desc) }}</textarea>
            </div>

        </div>

        <div class="flex justify-center mt-8">
            <button class="px-8 py-3 text-white bg-button-edit-bg rounded-md">
                ATUALIZAR
            </button>
        </div>

        <div class="flex justify-center">
            <a href="{{ route('nota_fiscal.index') }}"
                class="py-3 px-8 bg-gray-300 rounded-md">
                VOLTAR
            </a>
        </div>

    </form>
</div>

{{-- MODAL ORÇAMENTOS --}}
<div id="modalOrcamentos" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white w-4/5 max-w-5xl rounded-lg shadow-lg p-6">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Selecionar Orçamento</h2>
            <button type="button" id="fecharModalOrc" class="text-red-600 font-bold text-lg">✕</button>
        </div>

        <input type="text" id="buscarOrcamentoInput"
            placeholder="Buscar por cliente, ID..."
            class="w-full px-4 py-2 border rounded-md mb-4">

        <div class="overflow-y-auto max-h-96 border rounded-md">

            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">ID</th>
                        <th class="p-2 text-left">Cliente</th>
                        <th class="p-2 text-left">Data</th>
                        <th class="p-2"></th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($orcamentos as $orc)
                    <tr class="border-t orcamento-linha"
                        data-id="{{ $orc->id_orcamento }}"
                        data-cliente="{{ $orc->clienteOrcamento->clie_orc_nome ?? 'N/A' }}"
                        data-data="{{ \Carbon\Carbon::parse($orc->orc_data_inicio)->format('d/m/Y') }}">

                        <td class="p-2">#{{ $orc->id_orcamento }}</td>
                        <td class="p-2">{{ $orc->clienteOrcamento->clie_orc_nome ?? 'N/A' }}</td>
                        <td class="p-2">
                            {{ \Carbon\Carbon::parse($orc->orc_data_inicio)->format('d/m/Y') }}
                        </td>

                        <td class="p-2 text-right">
                            <button type="button"
                                class="selecionarOrcamento bg-green-600 text-white px-3 py-1 rounded">
                                Selecionar
                            </button>
                        </td>

                    </tr>
                    @endforeach

                </tbody>
            </table>

        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ===== MODAL =====
        const modal = document.getElementById('modalOrcamentos');
        document.getElementById('btnBuscarOrcamento').onclick = () => modal.classList.remove('hidden');
        document.getElementById('fecharModalOrc').onclick = () => modal.classList.add('hidden');

        document.querySelectorAll('.selecionarOrcamento').forEach(btn => {
            btn.onclick = function() {
                const linha = this.parentElement;
                document.getElementById('orcamento_id').value = linha.dataset.id;
                document.getElementById('orcamento_nome').value =
                    `#${linha.dataset.id} - ${linha.dataset.cliente} (${linha.dataset.data})`;
                modal.classList.add('hidden');
            }
        });

        // ===== FILTRO =====
        const filtro = document.getElementById('filtroDespesa');
        const selectTipo = document.getElementById('selectTipo');
        const movimentacao = document.getElementById('movimentacao');

        const tipos = Array.from(selectTipo.options);
        const movs = Array.from(movimentacao.options);

        function normalizar(t) {
            return t.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        }

        filtro.addEventListener('change', function() {
            selectTipo.innerHTML = '<option value="">Selecione</option>';

            tipos.forEach(opt => {
                if (opt.dataset.despesa === this.value) {
                    selectTipo.appendChild(opt);
                }
            });
        });

        selectTipo.addEventListener('change', function() {
            movimentacao.innerHTML = '<option value="">Selecione</option>';

            movs.forEach(opt => {
                if (!normalizar(opt.dataset.tipo).includes('caixa')) {
                    movimentacao.appendChild(opt);
                }
            });
        });

        // ===== VALOR =====
        const valorInput = document.getElementById('valorMask');
        const valorReal = document.getElementById('valorReal');

        if (valorReal.value) {
            let v = parseFloat(valorReal.value).toFixed(2).replace('.', ',');
            v = v.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            valorInput.value = 'R$ ' + v;
        }

        valorInput.addEventListener('input', function() {
            let v = this.value.replace(/\D/g, '');
            v = (v / 100).toFixed(2).replace('.', ',');
            v = v.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            this.value = 'R$ ' + v;
            valorReal.value = v.replace(/\./g, '').replace(',', '.');
        });

    });
</script>
@endpush