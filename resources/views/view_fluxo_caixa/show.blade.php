@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalhes do Fluxo de Caixa</h1>

        <div class="flex space-x-3">
            <a href="{{ route('fluxo_caixa.edit', $fluxo->id_fluxo) }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition">
                Editar
            </a>

            <a href="{{ route('fluxo_caixa.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition">
                Voltar
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


            <div class="mb-4">
                <p class="text-gray-600 text-sm">Data da Despessa</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ \Carbon\Carbon::parse($fluxo->flu_data_despesa)->format('d/m/Y') }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Tipo</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $fluxo->tipo->tipo_flu_nome ?? 'N/A' }}
                </p>
            </div>


            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Movimentação</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $fluxo->movimentacao->mov_nome ?? 'N/A' }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Valor</p>
                <p class="text-gray-900 text-lg font-semibold">
                    R$ {{ number_format($fluxo->flu_valor, 2, ',', '.') }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Descrição</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $fluxo->flu_desc }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Número do Documento</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $fluxo->flu_num_doc ?? 'N/A'}}
                </p>
            </div>

        </div>

    </div>

</div>
@endsection