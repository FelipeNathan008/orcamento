{{-- resources/views/view_forma_pagamento/create.blade.php --}}
@extends('layouts.app_financeiro')

@section('title', 'Cadastrar Nova Forma de Pagamento')

@section('content')
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

    @php
    $financeiroSelecionado = null;
    $valorPago = 0;
    $valorTotal = 0;
    $valorFaltante = 0;

    if(request('financeiro_id')) {
    $financeiroSelecionado = $financeiros->firstWhere('id_fin', request('financeiro_id'));

    // Pega todas as formas de pagamento do financeiro selecionado
    $formasDoFinanceiro = $formasPagamento->where('financeiro_id_fin', request('financeiro_id'));

    $valorTotal = $financeiroSelecionado->fin_valor_total ?? 0;
    $valorPago = $formasDoFinanceiro->sum('forma_valor');
    $valorFaltante = max($valorTotal - $valorPago, 0);
    }
    @endphp

    <form action="{{ route('forma_pagamento.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Card Financeiro --}}
            @if($financeiroSelecionado)
            <div class="md:col-span-2 bg-orange-50 border border-orange-200 rounded-lg p-6 shadow-sm">

                <h2 class="text-lg font-bold text-orange-700 mb-4">
                    Informações do Financeiro
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

                    <div>
                        <p class="text-gray-600">Cliente</p>
                        <p class="font-semibold text-gray-900">
                            {{ $financeiroSelecionado->fin_nome_cliente }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600">Valor Total</p>
                        <p class="font-semibold text-gray-900">
                            R$ {{ number_format($financeiroSelecionado->fin_valor_total, 2, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600">Status</p>
                        <p class="font-semibold text-gray-900">
                            {{ $financeiroSelecionado->fin_status }}
                        </p>
                    </div>

                </div>

            </div>

            {{-- ID oculto para envio no form --}}
            <input type="hidden" name="financeiro_id_fin" value="{{ $financeiroSelecionado->id_fin }}">
            @endif

            {{-- Select Tipo de Pagamento --}}
            <div class="md:col-span-1">
                <label for="tipo_pagamento_id_tipo" class="block text-sm font-medium text-custom-dark-text mb-1">Tipo de Pagamento</label>
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

            {{-- Valor --}}
            <div class="md:col-span-1">
                <label for="forma_valor" class="block text-sm font-medium text-custom-dark-text mb-1">Valor Total</label>
                <input type="number" step="0.01" name="forma_valor" id="forma_valor" value="{{ old('forma_valor') }}" required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                    placeholder="R$ 120,00">
                @error('forma_valor')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Competência --}}
            <div class="md:col-span-1">
                <label for="forma_mes" class="block text-sm font-medium text-custom-dark-text mb-1">Competência (Mês)</label>
                <input type="number" name="forma_mes" id="forma_mes" value="{{ old('forma_mes') }}" required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                    placeholder="11, 12...">
                @error('forma_mes')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            {{-- Prazo --}}
            <div class="md:col-span-1">
                <label for="forma_prazo" class="block text-sm font-medium text-custom-dark-text mb-1">Prazo</label>

                <select name="forma_prazo" id="forma_prazo" required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none
        focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">

                    <option value="">Selecione...</option>
                    <option value="À vista" {{ old('forma_prazo') == 'À vista' ? 'selected' : '' }}>À vista</option>
                    <option value="Parcelado" {{ old('forma_prazo') == 'Parcelado' ? 'selected' : '' }}>Parcelado</option>
                </select>

                @error('forma_prazo')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


            {{-- Quantidade de Parcelas --}}
            <div class="md:col-span-1">
                <label for="forma_qtd_parcela" class="block text-sm font-medium text-custom-dark-text mb-1">Qtd Parcelas</label>
                <input type="number" name="forma_qtd_parcela" id="forma_qtd_parcela" value="{{ old('forma_qtd_parcela') }}" required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none
               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                    placeholder="1, 2, 3...">
                @error('forma_qtd_parcela')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Descrição --}}
            <div class="md:col-span-2">
                <label for="forma_descricao" class="block text-sm font-medium text-custom-dark-text mb-1">Descrição</label>
                <textarea name="forma_descricao" id="forma_descricao" placeholder="Entrada, Chegada do Material..." required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">{{ old('forma_descricao') }}</textarea>
                @error('forma_descricao')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Valores totais --}}
        <div id="valorTotalPedido" class="mt-6 text-red-600 font-bold text-lg text-center">
            Valor Total do Pedido: R$ {{ number_format($valorTotal, 2, ',', '.') }}<br>
            Valor Pago: R$ {{ number_format($valorPago, 2, ',', '.') }}<br>
            Valor Faltante: R$ {{ number_format($valorFaltante, 2, ',', '.') }}
        </div>

        {{-- Script para atualizar valores dinamicamente --}}
        <script>
            const financeiros = JSON.parse('@json($financeiros)');
            const formasPagamento = JSON.parse('@json($formasPagamento)');
            const selectFinanceiro = document.getElementById('financeiro_id_fin_fake');
            const inputValor = document.getElementById('forma_valor');
            const valorTotalDiv = document.getElementById('valorTotalPedido');

            // Garantir que todas as variáveis sejam números
            let valorTotal = parseFloat('@json($valorTotal)') || 0;
            let valorPagoAtual = parseFloat('@json($valorPago)') || 0;
            let valorFaltante = Math.max(valorTotal - valorPagoAtual, 0);

            function atualizarValores() {
                let valorInput = parseFloat(inputValor.value) || 0;

                // Corrige automaticamente se o valor digitado ultrapassar o faltante
                if (valorInput > valorFaltante) {
                    valorInput = valorFaltante;
                    inputValor.value = valorFaltante.toFixed(2);
                }

                const faltante = Math.max(valorTotal - valorPagoAtual - valorInput, 0);

                valorTotalDiv.innerHTML = `
            Valor Total do Pedido: R$ ${valorTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}<br>
            Valor Pago: R$ ${valorPagoAtual.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}<br>
            Valor Faltante: R$ ${faltante.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
        `;
            }

            function atualizarFinanceiro() {
                const selectedId = parseInt(selectFinanceiro.value);

                const fin = financeiros.find(f => f.id_fin === selectedId);
                if (fin) {
                    valorTotal = parseFloat(fin.fin_valor_total) || 0;

                    const formasDoFin = formasPagamento.filter(f => f.financeiro_id_fin === selectedId);
                    valorPagoAtual = formasDoFin.reduce((sum, f) => sum + parseFloat(f.forma_valor || 0), 0);

                    valorFaltante = Math.max(valorTotal - valorPagoAtual, 0);

                    atualizarValores();
                }
            }

            selectFinanceiro.addEventListener('change', atualizarFinanceiro);
            inputValor.addEventListener('input', atualizarValores);

            window.addEventListener('DOMContentLoaded', () => {
                atualizarFinanceiro();
            });
        </script>

        <div class="flex justify-center mt-8">
            <button type="submit"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-button-save-bg hover:bg-button-save-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                SALVAR
            </button>
        </div>
        {{-- Botão Voltar unificado e movido para fora do formulário --}}
        <div class="flex justify-center mb-8">
            <a href="{{ url('/forma_pagamento?' . (int) request()->query('financeiro_id')) }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR PARA A LISTA
            </a>
        </div>
    </form>
</div>
@endsection