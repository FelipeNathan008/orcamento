@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalhes do Tipo de Fluxo de Caixa</h1>

        <div class="flex space-x-3">
            <a href="{{ route('tipo_fluxo_caixa.edit', $tipoFluxo->id_tipo_fluxo) }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition">
                Editar
            </a>

            <a href="{{ route('tipo_fluxo_caixa.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition">
                Voltar
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Nome</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $tipoFluxo->tipo_flu_nome }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Tipo de Despesa</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $tipoFluxo->tipo_despesa ?? '—' }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Descrição</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $tipoFluxo->tipo_desc }}
                </p>
            </div>

        </div>

    </div>

</div>
@endsection