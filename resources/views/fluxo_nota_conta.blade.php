@extends('layouts.app')

@section('title', 'Fluxos e Notas')

@section('content')
<div class="max-w-5xl mx-auto p-8 mt-10 mb-10">

    <h1 class="text-3xl font-bold text-center mb-8">
        Fluxos de Caixa e Notas Fiscais
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <a href="{{ route('fluxo_caixa.index') }}"
            class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition border border-gray-200">
            <h2 class="text-xl font-bold text-orange-600">Fluxos de Caixa</h2>
            <p class="text-gray-600 mt-2">
                Gerenciar Fluxos de Caixas.
            </p>
        </a>

        <a href="{{ route('nota_fiscal.index') }}"
            class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition border border-gray-200">
            <h2 class="text-xl font-bold text-orange-600">Notas Fiscais</h2>
            <p class="text-gray-600 mt-2">
                Gerenciar Notas Fiscais.
            </p>
        </a>

        <a href="{{ route('conta_bancaria.index') }}"
            class="bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition border border-gray-200">
            <h2 class="text-xl font-bold text-orange-600">Saldos em Contas Bancárias</h2>
            <p class="text-gray-600 mt-2">
                Gerenciar Saldos em Contas Bancárias.
            </p>
        </a>
    </div>

</div>
@endsection