@extends('layouts.app')

@section('title', 'Editar Tipo de Fluxo de Caixa')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Editar Tipo de Fluxo de Caixa
    </h1>

    {{-- ERROS --}}
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <strong>Opa!</strong>
        <span>Existem alguns problemas:</span>
        <ul class="mt-3 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('tipo_fluxo_caixa.update', $tipoFluxo->id_tipo_fluxo) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

            {{-- COLUNA ESQUERDA --}}
            <div class="space-y-6">

                <div>
                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Nome
                    </label>
                    <input type="text" name="tipo_flu_nome"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500"
                        value="{{ old('tipo_flu_nome', $tipoFluxo->tipo_flu_nome) }}"
                        maxlength="120"
                        required>
                </div>

                <div>

                    <div>
                        <label class="block text-sm font-medium text-custom-dark-text mb-1">
                            Despesa
                        </label>

                        <select name="tipo_despesa"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500"
                            required>

                            <option value="">Selecione</option>

                            <option value="Fixa"
                                {{ old('tipo_despesa', $tipoFluxo->tipo_despesa) == 'Fixa' ? 'selected' : '' }}>
                                Fixa
                            </option>

                            <option value="Variavel"
                                {{ old('tipo_despesa', $tipoFluxo->tipo_despesa) == 'Variavel' ? 'selected' : '' }}>
                                Variável
                            </option>

                        </select>
                    </div>
                </div>

            </div>

            {{-- COLUNA DIREITA --}}
            <div class="space-y-6">

                <div>
                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Descrição
                    </label>
                    <input type="text" name="tipo_desc"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500"
                        value="{{ old('tipo_desc', $tipoFluxo->tipo_desc) }}"
                        maxlength="180"
                        required>
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
            <a href="{{ route('tipo_fluxo_caixa.index') }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR PARA A LISTA
            </a>
        </div>

    </form>
</div>
@endsection