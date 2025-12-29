@extends('layouts.app_financeiro')

@section('title', 'Cadastrar Financeiro')

@section('content')
    <div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

        {{-- Título --}}
        <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Cadastro Financeiro</h1>

        {{-- Mensagens de erro do Laravel --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('financeiro.store') }}" method="POST" id="financeiroForm" class="space-y-6">
            @csrf

            {{-- ID Orcamento Principal --}}
            <div>
                <label for="orcamento_id_orcamento" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Código do Orçamento
                </label>
                <input type="number" name="orcamento_id_orcamento" id="orcamento_id_orcamento"
                       class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none
                       focus:ring-2 focus:ring-blue-500 border border-gray-300"
                       placeholder="Informe o código do orçamento"
                       value="{{ old('orcamento_id_orcamento') }}" required>

                @error('orcamento_id_orcamento')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- ID Orcamento Interno --}}
            <div>
                <label for="id_orcamento" class="block text-sm font-medium text-custom-dark-text mb-1">
                    ID do Orçamento (Interno)
                </label>
                <input type="number" name="id_orcamento" id="id_orcamento"
                       class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none
                       focus:ring-2 focus:ring-blue-500 border border-gray-300"
                       placeholder="Informe o ID do orçamento"
                       value="{{ old('id_orcamento') }}" required>

                @error('id_orcamento')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- ID Cliente --}}
            <div>
                <label for="id_cliente" class="block text-sm font-medium text-custom-dark-text mb-1">
                    ID do Cliente
                </label>
                <input type="number" name="id_cliente" id="id_cliente"
                       class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none
                       focus:ring-2 focus:ring-blue-500 border border-gray-300"
                       placeholder="Informe o ID do cliente"
                       value="{{ old('id_cliente') }}" required>

                @error('id_cliente')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nome do Cliente --}}
            <div>
                <label for="fin_nome_cliente" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Nome do Cliente
                </label>
                <input type="text" name="fin_nome_cliente" id="fin_nome_cliente"
                       class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none
                       focus:ring-2 focus:ring-blue-500 border border-gray-300"
                       placeholder="Informe o nome do cliente"
                       maxlength="90"
                       value="{{ old('fin_nome_cliente') }}" required>

                @error('fin_nome_cliente')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Valor Total --}}
            <div>
                <label for="fin_valor_total" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Valor Total
                </label>
                <input type="number" step="0.01" name="fin_valor_total" id="fin_valor_total"
                       class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none
                       focus:ring-2 focus:ring-blue-500 border border-gray-300"
                       placeholder="Informe o valor total"
                       value="{{ old('fin_valor_total') }}" required>

                @error('fin_valor_total')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div>
                <label for="fin_status" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Status
                </label>
                <input type="text" name="fin_status" id="fin_status"
                       class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none
                       focus:ring-2 focus:ring-blue-500 border border-gray-300"
                       placeholder="Informe o status"
                       maxlength="45"
                       value="{{ old('fin_status') }}" required>

                @error('fin_status')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botão Salvar --}}
            <div class="flex justify-center mt-8">
                <button type="submit"
                    class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium
                    rounded-md text-white bg-button-save-bg hover:bg-button-save-hover focus:outline-none
                    focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                    SALVAR
                </button>
            </div>

        </form>
    </div>

    {{-- Botão Voltar --}}
    <div class="flex justify-center mb-8">
        <a href="{{ route('financeiro.index') }}"
           class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium
           rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition">
            VOLTAR PARA A LISTA
        </a>
    </div>
@endsection
