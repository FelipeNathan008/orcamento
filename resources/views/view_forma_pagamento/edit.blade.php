{{-- resources/views/view_forma_pagamento/edit.blade.php --}}
@extends('layouts.app_financeiro')

@section('title', 'Editar Forma de Pagamento')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">
    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Editar Forma de Pagamento</h1>

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

    <form action="{{ route('forma_pagamento.update', $formaPagamento->id_forma_pag) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Select Financeiro --}}
            <div class="md:col-span-1">
                <label for="financeiro_id_fin" class="block text-sm font-medium text-custom-dark-text mb-1">Financeiro</label>
                <select name="financeiro_id_fin" id="financeiro_id_fin" required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                    <option value="">Selecione</option>
                    @foreach ($financeiros as $fin)
                    <option value="{{ $fin->id_fin }}" {{ $formaPagamento->financeiro_id_fin == $fin->id_fin ? 'selected' : '' }}>
                        Cliente: {{ $fin->fin_nome_cliente }} | Valor: R$ {{ number_format($fin->fin_valor_total, 2, ',', '.') }} | Status: {{ $fin->fin_status }}
                    </option>
                    @endforeach
                </select>
                @error('financeiro_id_fin')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Select Tipo de Pagamento --}}
            <div class="md:col-span-1">
                <label for="tipo_pagamento_id_tipo" class="block text-sm font-medium text-custom-dark-text mb-1">Tipo de Pagamento</label>
                <select name="tipo_pagamento_id_tipo" id="tipo_pagamento_id_tipo" required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                    <option value="">Selecione</option>
                    @foreach ($tiposPagamento as $tipo)
                    <option value="{{ $tipo->id_tipo_pagamento }}" {{ $formaPagamento->tipo_pagamento_id_tipo == $tipo->id_tipo_pagamento ? 'selected' : '' }}>
                        {{ $tipo->tipo_plano_fin }}
                    </option>
                    @endforeach
                </select>
                @error('tipo_pagamento_id_tipo')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Valor --}}
            <div class="md:col-span-1">
                <label for="forma_valor" class="block text-sm font-medium text-custom-dark-text mb-1">Valor</label>
                <input type="number" step="0.01" name="forma_valor" id="forma_valor" value="{{ old('forma_valor', $formaPagamento->forma_valor) }}" required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                    placeholder="R$ 120,00">
                @error('forma_valor')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Competência (mês) --}}
            <div class="md:col-span-1">
                <label for="forma_mes" class="block text-sm font-medium text-custom-dark-text mb-1">Competência (Mês)</label>
                <input type="number" name="forma_mes" id="forma_mes" value="{{ old('forma_mes', $formaPagamento->forma_mes) }}" required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                    placeholder="11, 12...">
                @error('forma_mes')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descrição --}}
            <div class="md:col-span-2">
                <label for="forma_descricao" class="block text-sm font-medium text-custom-dark-text mb-1">Descrição</label>
                <textarea name="forma_descricao" id="forma_descricao" placeholder="Entrada, Chegada do Material..." required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">{{ old('forma_descricao', $formaPagamento->forma_descricao) }}</textarea>
                @error('forma_descricao')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Prazo --}}
            <div class="md:col-span-1">
                <label for="forma_prazo" class="block text-sm font-medium text-custom-dark-text mb-1">Prazo</label>
                <input type="text" name="forma_prazo" id="forma_prazo" value="{{ old('forma_prazo', $formaPagamento->forma_prazo) }}" required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                    placeholder="À vista, 30 dias...">
                @error('forma_prazo')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Quantidade de Parcelas --}}
            <div class="md:col-span-1">
                <label for="forma_qtd_parcela" class="block text-sm font-medium text-custom-dark-text mb-1">Quantidade de Parcelas</label>
                <input type="number" name="forma_qtd_parcela" id="forma_qtd_parcela" value="{{ old('forma_qtd_parcela', $formaPagamento->forma_qtd_parcela) }}" required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                    placeholder="2, 3, 4...">
                @error('forma_qtd_parcela')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- Valor total do pedido --}}
        <div id="valorTotalPedido" class="mt-6 text-red-600 font-bold text-lg text-center">
            Valor total do pedido: R$ 0,00
        </div>



        <script>
            const financeiros = JSON.parse('@json($financeiros)');
            const formasPagamento = JSON.parse('@json($formasPagamento)');
            const selectFinanceiro = document.getElementById('financeiro_id_fin');
            const inputValor = document.getElementById('forma_valor');
            const valorTotalDiv = document.getElementById('valorTotalPedido');

            let valorTotal = 0;
            let valorPagoAtual = 0;

            function atualizarValores() {
                const valorInput = parseFloat(inputValor.value) || 0;
                const faltante = Math.max(valorTotal - valorPagoAtual - valorInput, 0);

                valorTotalDiv.innerHTML = `
            Valor Total do Pedido: R$ ${valorTotal.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}<br>
            Valor Pago: R$ ${(valorPagoAtual + valorInput).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}<br>
            Valor Faltante: R$ ${faltante.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
        `;
            }

            function atualizarFinanceiro() {
                const selectedId = parseInt(selectFinanceiro.value);

                if (!selectedId) {
                    valorTotal = 0;
                    valorPagoAtual = 0;
                    inputValor.value = '';
                    atualizarValores();
                    return;
                }

                const fin = financeiros.find(f => f.id_fin === selectedId);

                if (fin) {
                    valorTotal = parseFloat(fin.fin_valor_total);

                    const formasDoFin = formasPagamento.filter(f =>
                        f.financeiro_id_fin === selectedId &&
                        f.id_forma_pag !== parseInt('{{ $formaPagamento->id_forma_pag }}')
                    );

                    valorPagoAtual = formasDoFin.reduce((sum, f) => sum + parseFloat(f.forma_valor), 0);

                    atualizarValores();
                }
            }

            document.querySelector("form").addEventListener("submit", function(e) {

                const valorDigitado = parseFloat(inputValor.value) || 0;

                // usar as variáveis reais
                const valorMaximo = valorTotal - valorPagoAtual;

                if (valorDigitado > valorMaximo) {
                    e.preventDefault();
                    alert("❌ O valor informado é maior do valor faltante!");
                }

            });


            selectFinanceiro.addEventListener('change', atualizarFinanceiro);

            window.addEventListener('DOMContentLoaded', () => {
                if (selectFinanceiro.value) {
                    atualizarFinanceiro();
                }
            });
        </script>



        {{-- Botão de envio --}}
        <div class="flex justify-center mt-8">
            <button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                ATUALIZAR FORMA DE PAGAMENTO
            </button>
        </div>
    </form>
</div>

{{-- Botão Voltar --}}
<div class="flex justify-center mb-8">
    <a href="{{ route('forma_pagamento.index', ['search' => $formaPagamento->financeiro_id_fin]) }}"
        class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
        VOLTAR PARA A LISTA
    </a>
</div>

@endsection