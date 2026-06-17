@extends('layouts.app')

@section('title', 'Cadastrar Nota Fiscal')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Nova Nota Fiscal
    </h1>

    {{-- ERROS --}}
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4">
        {{ session('error') }}
    </div>
    @endif

    <form id="notaForm" action="{{ route('nota_fiscal.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- COLUNA ESQUERDA --}}
            <div class="space-y-6">

                {{-- ORÇAMENTO --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Orçamento</label>

                    <div class="flex gap-3">
                        <input type="text" id="orcamento_nome"
                            placeholder="Nenhum orçamento selecionado"
                            class="w-full px-4 py-2 border rounded-md bg-gray-100"
                            readonly>

                        <button type="button" id="btnBuscarOrcamento"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            🔎 Buscar
                        </button>
                    </div>

                    <input type="hidden" name="orcamento_id_orcamento" id="orcamento_id" required>
                </div>

                {{-- DATA --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Data</label>
                    <input type="date" name="nota_data" id="dataHoje"
                        class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                        required>
                </div>

                {{-- NUMERO --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Número da Nota</label>
                    <input type="text" name="nota_numero"
                        class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                        placeholder="Ex: NF-1024"
                        required>
                </div>

            </div>

            {{-- COLUNA DIREITA --}}
            <div class="space-y-6">

                {{-- TIPO DESPESA --}}
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

                    <select name="nota_id_tipo" id="selectTipo"
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

                    <select name="nota_id_movimentacao" id="movimentacao"
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

        </div>

        {{-- SEGUNDA LINHA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- VALOR --}}
            <div>
                <label class="block text-sm font-medium mb-1">Valor</label>

                <input type="text" id="valorMask"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                    placeholder="R$ 0,00"
                    required>

                <input type="hidden" name="nota_valor" id="valorReal">
            </div>

            {{-- DESCRIÇÃO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Descrição</label>
                <textarea name="nota_desc"
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500"
                    maxlength="255"
                    required></textarea>
            </div>

        </div>

        {{-- BOTÕES --}}
        <div class="flex justify-center mt-8">
            <button id="btnSalvar"
                class="px-8 py-3 text-white rounded-md bg-button-save-bg">
                SALVAR
            </button>
        </div>

        <div class="flex justify-center mb-8">
            <a href="{{ route('nota_fiscal.index') }}"
                class="py-3 px-8 bg-gray-300 rounded-md hover:bg-gray-400">
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

        // =========================
        // MODAL ORÇAMENTO
        // =========================
        const modalOrc = document.getElementById('modalOrcamentos');
        const btnBuscarOrc = document.getElementById('btnBuscarOrcamento');
        const fecharOrc = document.getElementById('fecharModalOrc');
        const inputBuscaOrc = document.getElementById('buscarOrcamentoInput');

        const campoNomeOrc = document.getElementById('orcamento_nome');
        const campoIdOrc = document.getElementById('orcamento_id');

        // ABRIR MODAL
        btnBuscarOrc.addEventListener('click', () => {
            modalOrc.classList.remove('hidden');
            modalOrc.classList.add('flex');
        });

        // FECHAR MODAL
        fecharOrc.addEventListener('click', () => {
            modalOrc.classList.add('hidden');
        });

        // BUSCA DINÂMICA
        inputBuscaOrc.addEventListener('keyup', function() {

            const termo = this.value.toLowerCase();
            const linhas = document.querySelectorAll('.orcamento-linha');

            let contador = 0;

            linhas.forEach(linha => {

                const texto = linha.innerText.toLowerCase();

                if (texto.includes(termo) && contador < 20) {
                    linha.style.display = '';
                    contador++;
                } else {
                    linha.style.display = 'none';
                }

            });

        });

        // SELECIONAR ORÇAMENTO
        document.querySelectorAll('.selecionarOrcamento').forEach(btn => {

            btn.addEventListener('click', function() {

                const linha = this.closest('tr');

                const id = linha.dataset.id;
                const cliente = linha.dataset.cliente;
                const data = linha.dataset.data;

                campoIdOrc.value = id;
                campoNomeOrc.value = `#${id} - ${cliente} (${data})`;

                modalOrc.classList.add('hidden');

            });

        });

        // DATA HOJE
        document.getElementById('dataHoje').value =
            new Date().toISOString().split('T')[0];

        const form = document.getElementById('notaForm');
        const btn = document.getElementById('btnSalvar');

        form.addEventListener('submit', function() {
            if (btn.disabled) return false;
            btn.disabled = true;
            btn.innerText = 'SALVANDO...';
        });

        // =========================
        // FILTRO DESPESA → TIPO
        // =========================
        const filtro = document.getElementById('filtroDespesa');
        const selectTipo = document.getElementById('selectTipo');
        const movimentacao = document.getElementById('movimentacao');

        const tipos = Array.from(selectTipo.options);
        const movs = Array.from(movimentacao.options);

        selectTipo.disabled = true;
        movimentacao.disabled = true;

        filtro.addEventListener('change', function() {

            const valor = this.value;

            selectTipo.innerHTML = '<option value="">Selecione</option>';
            movimentacao.innerHTML = '<option value="">Selecione</option>';

            if (!valor) {
                selectTipo.disabled = true;
                movimentacao.disabled = true;
                return;
            }

            selectTipo.disabled = false;

            tipos.forEach(opt => {
                if (!opt.value) return;

                if (opt.dataset.despesa === valor) {
                    selectTipo.appendChild(opt);
                }
            });

            movimentacao.disabled = true;
        });

        // TIPO → MOVIMENTAÇÃO (SEM BLOQUEAR CAIXA)
        selectTipo.addEventListener('change', function() {

            movimentacao.innerHTML = '<option value="">Selecione</option>';

            if (!this.value) {
                movimentacao.disabled = true;
                return;
            }

            movimentacao.disabled = false;

            function normalizar(texto) {
                return texto.toLowerCase()
                    .normalize("NFD")
                    .replace(/[\u0300-\u036f]/g, "");
            }

            movs.forEach(opt => {

                if (!opt.value) return;

                let nome = normalizar(opt.dataset.tipo || '');

                if (nome.includes('caixa')) return;

                movimentacao.appendChild(opt);

            });
        });

        // MÁSCARA VALOR

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

        form.addEventListener('submit', function() {
            valorReal.value = valorInput.value
                .replace('R$ ', '')
                .replace(/\./g, '')
                .replace(',', '.');
        });

    });
</script>
@endpush