@extends('layouts.app_financeiro')

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalhes Financeiros</h1>

        <div class="flex space-x-3">

            <a href="{{ route('financeiro.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Voltar para a Lista
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="mb-4">
                <p class="text-gray-600 text-sm">ID Financeiro:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $financeiro->id_fin }}</p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">ID Orçamento (FK):</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $financeiro->orcamento_id_orcamento }}</p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">ID Orçamento:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $financeiro->id_orcamento }}</p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">ID Cliente:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $financeiro->id_cliente }}</p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Nome do Cliente:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $financeiro->fin_nome_cliente }}</p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Valor Total:</p>
                <p class="text-gray-900 text-lg font-semibold">R$ {{ number_format($financeiro->fin_valor_total, 2, ',', '.') }}</p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Status:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $financeiro->fin_status }}</p>
            </div>

        </div>
    </div>
</div>
@endsection
