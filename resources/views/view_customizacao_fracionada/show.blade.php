@extends('layouts.app_financeiro')

@section('title', 'Detalhes da Customização (Fracionado)')

@section('content')

<div class="max-w-3xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-custom-dark-text">
            Customização — Fracionado #{{ $orcamentoFracionado->orc_fracao }}
        </h1>
        <a href="{{ route('customizacao_fracionado.index', ['id_det_fracionado' => $detalhe->id_det_fracionado]) }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition">
            VOLTAR
        </a>
    </div>

    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">
        <h2 class="text-lg font-bold text-orange-700 mb-4">Produto</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-600">Produto</p>
                <p class="font-semibold">
                    {{ $detalhe->produto->prod_cod ?? 'N/A' }} - {{ $detalhe->produto->prod_nome ?? 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold">{{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-600">Tamanho</p>
                <p class="font-semibold">{{ $detalhe->det_tamanho ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="border border-gray-200 rounded-lg p-6 shadow-sm bg-white space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <p><span class="text-gray-500">Tipo:</span> <span class="font-semibold">{{ $customizacao->cust_tipo }}</span></p>
            <p><span class="text-gray-500">Local:</span> <span class="font-semibold">{{ $customizacao->cust_local }}</span></p>
            <p><span class="text-gray-500">Posição:</span> <span class="font-semibold">{{ $customizacao->cust_posicao }}</span></p>
            <p><span class="text-gray-500">Tamanho:</span> <span class="font-semibold">{{ $customizacao->cust_tamanho }}</span></p>
            <p><span class="text-gray-500">Formatação:</span> <span class="font-semibold">{{ $customizacao->cust_formatacao }}</span></p>
            <p><span class="text-gray-500">Valor:</span> <span class="font-semibold">R$ {{ number_format($customizacao->cust_valor, 2, ',', '.') }}</span></p>
        </div>

        @if($customizacao->cust_descricao)
            <div class="pt-4 border-t border-gray-200">
                <p class="text-gray-500 text-sm mb-1">Descrição</p>
                <p class="text-sm text-gray-800">{{ $customizacao->cust_descricao }}</p>
            </div>
        @endif

        @if($customizacao->cust_imagem)
            <div class="pt-4 border-t border-gray-200">
                <p class="text-gray-500 text-sm mb-2">Imagem</p>
                <img src="{{ asset('images_customizacoes/' . $customizacao->cust_imagem) }}"
                    class="w-40 h-40 object-cover rounded shadow">
            </div>
        @endif
    </div>

</div>

@endsection