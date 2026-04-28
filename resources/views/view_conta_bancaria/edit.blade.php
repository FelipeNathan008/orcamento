@extends('layouts.app')

@section('title', 'Editar Conta Bancária')

@section('content')
<div class="max-w-4xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Editar Conta Bancária
    </h1>

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('conta_bancaria.update', $conta->id_conta ?? $conta->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- BANCO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Nome do Banco</label>
                <input type="text" name="conta_nome_banco"
                    value="{{ old('conta_nome_banco', $conta->conta_nome_banco) }}"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                    required>
            </div>

            {{-- CODIGO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Código do Banco</label>
                <input type="text" name="conta_cod_banco"
                    value="{{ old('conta_cod_banco', $conta->conta_cod_banco) }}"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                    required>
            </div>

            {{-- CONTA --}}
            <div>
                <label class="block text-sm font-medium mb-1">Número da Conta</label>
                <input type="text" name="numero_conta_corrente"
                    value="{{ old('numero_conta_corrente', $conta->numero_conta_corrente) }}"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                    required>
            </div>

            {{-- DIGITO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Dígito</label>
                <input type="text" name="numero_digito_corrente"
                    value="{{ old('numero_digito_corrente', $conta->numero_digito_corrente) }}"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                    required>
            </div>

            {{-- DESCRIÇÃO --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Descrição</label>
                <textarea name="conta_desc" rows="4"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                    maxlength="255">{{ old('conta_desc', $conta->conta_desc) }}</textarea>
            </div>

        </div>

        <div class="flex justify-center mt-8">
            <button type="submit"
                class="inline-flex justify-center py-2 px-6 text-white bg-button-edit-bg hover:bg-button-edit-hover rounded-md">
                ATUALIZAR
            </button>
        </div>

        <div class="flex justify-center mt-4">
            <a href="{{ route('conta_bancaria.index') }}"
                class="py-2 px-6 bg-gray-300 hover:bg-gray-400 rounded-md">
                VOLTAR
            </a>
        </div>

    </form>
</div>
@endsection