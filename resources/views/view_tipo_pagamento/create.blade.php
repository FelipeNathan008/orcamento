<!-- resources/views/tipo_pagamento/create.blade.php -->
@extends('layouts.app_financeiro')

@section('title', 'Cadastrar Novo Tipo de Pagamento')

@section('content')
{{-- Contêiner principal centralizado --}}
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">
    {{-- Título centralizado --}}
    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Cadastro de Novo Tipo de Pagamento</h1>

    {{-- Formulário --}}
    <form action="{{ route('tipo_pagamento.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Grid principal (usado apenas para manter o padrão visual) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Campo Tipo de Plano Financeiro - ocupa duas colunas --}}
            <div class="md:col-span-2">
                <label for="tipo_plano_fin" class="block text-sm font-medium text-custom-dark-text mb-1">Tipo de Plano Financeiro</label>
                <input type="text"
                    name="tipo_plano_fin"
                    id="tipo_plano_fin"
                    class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                    placeholder="Ex: Boleto, Pix..."
                    maxlength="60"
                    value="{{ old('tipo_plano_fin') }}"
                    required>
                @error('tipo_plano_fin')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div> {{-- Fim do grid --}}

        <div class="flex justify-center mt-8">
            <button type="submit"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-button-save-bg hover:bg-button-save-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                SALVAR
            </button>
        </div>
        {{-- Botão Voltar unificado e movido para fora do formulário --}}
        <div class="flex justify-center mb-8">
            <a href="{{ route('tipo_pagamento.index') }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR PARA A LISTA
            </a>
        </div>
    </form>
</div>

@endsection