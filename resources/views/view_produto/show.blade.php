@extends('layouts.app') {{-- Assumindo que você tem um layout principal chamado 'app' --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalhes do Produto</h1>
        <div class="flex space-x-3">
            <a href="{{ route('produto.edit', $produto->id_produto) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Editar Produto
            </a>
            <a href="{{ route('produto.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Voltar para a Lista
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <p class="text-gray-600 text-sm">ID do Produto:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $produto->id_produto }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Código do Produto:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $produto->prod_cod }}</p>
            </div>
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Nome do Produto:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $produto->prod_nome }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Família:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $produto->prod_familia }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Categoria:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $produto->prod_categoria }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Material:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $produto->prod_material }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Gênero:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $produto->prod_genero }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Modelo:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $produto->prod_modelo }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Características:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $produto->prod_caract }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Cor:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $produto->prod_cor }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Tamanhos:</p> {{-- Adicionado o campo de Tamanhos --}}
                <p class="text-gray-900 text-lg font-semibold">
                    @if(is_array($produto->prod_tamanho))
                        {{ implode(', ', $produto->prod_tamanho) }}
                    @else
                        {{ $produto->prod_tamanho }}
                    @endif
                </p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Preço:</p>
                <p class="text-gray-900 text-lg font-semibold">R$ {{ number_format($produto->prod_preco, 2, ',', '.') }}</p>
            </div>
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Criado em:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $produto->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Última Atualização:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $produto->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
