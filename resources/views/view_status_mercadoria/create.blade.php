@extends('layouts.app')

@section('title', 'Cadastrar Novo Status de Mercadoria')

@section('content')
    <div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

        {{-- Título --}}
        <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
            Cadastro de Novo Status de Mercadoria
        </h1>

        {{-- Mensagem de erro unificada --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('status_mercadoria.store') }}" method="POST" id="statusForm" class="space-y-6">
            @csrf

            {{-- GRID PRINCIPAL — mantido igual ao molde --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- ÚNICO CAMPO — alinhado no mesmo padrão dos campos do molde --}}
                <div>
                    <label for="status_merc_nome" class="block text-sm font-medium text-custom-dark-text mb-1">
                        Nome do Status
                    </label>
                    <input type="text" name="status_merc_nome" id="status_merc_nome"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md
                               outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Ex.: Em Separação, A Caminho, Entregue..."
                        maxlength="90"
                        value="{{ old('status_merc_nome') }}"
                        required>

                    @error('status_merc_nome')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div> {{-- fim grid --}}

            {{-- Botão salvar --}}
            <div class="flex justify-center mt-8">
                <button type="submit"
                    class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm 
                           text-base font-medium rounded-md text-white bg-button-save-bg
                           hover:bg-button-save-hover focus:outline-none focus:ring-2 focus:ring-offset-2
                           focus:ring-orange-500 transition duration-150 ease-in-out">
                    SALVAR
                </button>
            </div>
        </form>
    </div>

    {{-- Botão voltar — mesmo estilo do molde --}}
    <div class="flex justify-center mb-8">
        <a href="{{ route('status_mercadoria.index') }}"
            class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm 
                   text-base font-medium rounded-md text-custom-dark-text bg-gray-300
                   hover:bg-gray-400 transition duration-150 ease-in-out">
            VOLTAR PARA A LISTA
        </a>
    </div>
@endsection
