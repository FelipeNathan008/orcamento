@extends('layouts.app')

@section('title', 'Administração')

@section('content')
<div class="max-w-5xl mx-auto p-8 mt-10 mb-10">

    <h1 class="text-3xl font-bold text-center mb-8">
        Gerenciamento
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <a href="{{ route('produto.index') }}"
            class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition border border-gray-200">
            <h2 class="text-xl font-bold text-orange-600">Produtos</h2>
            <p class="text-gray-600 mt-2">
                Gerenciar produtos cadastrados.
            </p>
        </a>

        <a href="{{ route('preco_customizacao.index') }}"
            class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition border border-gray-200">
            <h2 class="text-xl font-bold text-orange-600">Preço Customização</h2>
            <p class="text-gray-600 mt-2">
                Gerenciar preços de customização.
            </p>
        </a>

        <a href="{{ route('tipo_fluxo_caixa.index') }}"
            class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition border border-gray-200">
            <h2 class="text-xl font-bold text-orange-600">Tipo Fluxo Caixa</h2>
            <p class="text-gray-600 mt-2">
                Gerenciar os tipos de fluxo de caixa.
            </p>
        </a>

        <a href="{{ route('tipo_pagamento.index') }}"
            class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition border border-gray-200">
            <h2 class="text-xl font-bold text-orange-600">Tipo Pagamento</h2>
            <p class="text-gray-600 mt-2">
                Gerencias os Tipo de Pagamento.
            </p>
        </a>
        
    </div>

</div>
@endsection