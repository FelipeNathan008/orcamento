@extends('layouts.app')

@section('title', 'Criar Conta Bancária')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Criar Conta Bancária
    </h1>

    {{-- SUCESSO --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    {{-- ERROS --}}
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('conta_bancaria.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium mb-1">Nome do Banco</label>
                <input type="text" name="conta_nome_banco"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                    value="{{ old('conta_nome_banco') }}"
                    maxlength="200" placeholder="Itaú"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Código do Banco</label>
                <input type="text" name="conta_cod_banco"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                    value="{{ old('conta_cod_banco') }}"
                    maxlength="10" placeholder="341"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Conta Corrente</label>
                <input type="text" name="numero_conta_corrente"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                    value="{{ old('numero_conta_corrente') }}"
                    maxlength="100" placeholder="x-yyyyy"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Dígito</label>
                <input type="text" name="numero_digito_corrente"
                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-500"
                    value="{{ old('numero_digito_corrente') }}"
                    maxlength="90" placeholder="xxxxx-y"
                    required>
            </div>

        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Descrição</label>
            <textarea name="conta_desc"
                rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500"
                maxlength="255" placeholder="Cartão exclusivo para Alphamega"
                required>{{ old('conta_desc') }}</textarea>
        </div>
        {{-- BOTÕES --}}
        <div class="flex justify-center mt-8">
            <button class="px-8 py-3 text-white rounded-md bg-button-save-bg">
                SALVAR
            </button>
        </div>

        <div class="flex justify-center mb-8">
            <a href="{{ route('conta_bancaria.index') }}"
                class="px-8 py-3 rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400">
                VOLTAR
            </a>
        </div>

    </form>
</div>
@endsection