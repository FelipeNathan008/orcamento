@extends('layouts.app')

@section('title', 'Criar Tipo de Fluxo de Caixa')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Criar Tipo Fluxo de Caixa
    </h1>

    {{-- SUCESSO --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <strong>Sucesso!</strong>
        <span>{{ session('success') }}</span>
    </div>
    @endif

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

    <form action="{{ route('tipo_fluxo_caixa.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

            {{-- COLUNA ESQUERDA --}}
            <div class="space-y-6">

                <div>
                    <label for="tipo_flu_nome" class="block text-sm font-medium text-custom-dark-text mb-1">
                        Nome
                    </label>
                    <input type="text" name="tipo_flu_nome" id="tipo_flu_nome" placeholder="Papelaria"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500"
                        value="{{ old('tipo_flu_nome') }}"
                        maxlength="120"
                        required>
                </div>

                <div>
                    <label for="tipo_despesa" class="block text-sm font-medium text-custom-dark-text mb-1">
                        Tipo de Despesa
                    </label>

                    <select name="tipo_despesa" id="tipo_despesa"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500"
                        required>

                        <option value="">Selecione</option>

                        <option value="Fixa"
                            {{ old('tipo_despesa', $tipoFluxo->tipo_despesa ?? '') == 'Fixa' ? 'selected' : '' }}>
                            Fixa
                        </option>

                        <option value="Variavel"
                            {{ old('tipo_despesa', $tipoFluxo->tipo_despesa ?? '') == 'Variavel' ? 'selected' : '' }}>
                            Variável
                        </option>

                    </select>
                </div>

            </div>

            {{-- COLUNA DIREITA --}}
            <div class="space-y-6">

                <div>
                    <label for="tipo_desc" class="block text-sm font-medium text-custom-dark-text mb-1">
                        Descrição
                    </label>
                    <input type="text" name="tipo_desc" id="tipo_desc" placeholder="Fluxos da Papelaria"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500"
                        value="{{ old('tipo_desc') }}"
                        maxlength="180"
                        required>
                </div>
            </div>

        </div>

        {{-- BOTÃO SALVAR --}}
        <div class="flex justify-center mt-8">
            <button type="submit"
                class="px-8 py-3 text-white rounded-md shadow-sm bg-button-save-bg hover:bg-button-save-hover">
                SALVAR
            </button>
        </div>

        {{-- BOTÃO VOLTAR --}}
        <div class="flex justify-center mb-8">
            <a href="{{ route('tipo_fluxo_caixa.index') }}"
                class="px-8 py-3 rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400">
                VOLTAR PARA A LISTA
            </a>
        </div>

    </form>
</div>
@endsection