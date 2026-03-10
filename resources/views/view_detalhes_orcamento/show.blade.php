@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            Detalhes do Item do Orçamento
        </h1>

        <div class="flex space-x-3">

            <a href="{{ route('detalhes_orcamento.edit', $detalheOrcamento->id_det) }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Editar Item
            </a>

            <a href="{{ route('detalhes_orcamento.index', ['orcamento_id' => $detalheOrcamento->orcamento_id_orcamento]) }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Voltar para a Lista
            </a>

        </div>
    </div>


    <div class="bg-white shadow-xl rounded-lg p-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="mb-4">
                <p class="text-gray-600 text-sm">ID do Orçamento:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->orcamento_id_orcamento }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Cliente:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Produto:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->produto->prod_nome ?? 'N/A' }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Código:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_cod }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Categoria:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_categoria }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Modelo:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_modelo }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Cor:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_cor }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Gênero:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_genero }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Tamanho:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_tamanho }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Quantidade:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_quantidade }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Valor Unitário:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    R$ {{ number_format($detalheOrcamento->det_valor_unit, 2, ',', '.') }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Total:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    R$ {{ number_format($detalheOrcamento->det_quantidade * $detalheOrcamento->det_valor_unit, 2, ',', '.') }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Características:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_caract ?? 'N/A' }}
                </p>
            </div>

            @if($detalheOrcamento->det_observacao)
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Observação:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_observacao }}
                </p>
            </div>
            @endif

            @if($detalheOrcamento->det_anotacao)
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Anotação:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_anotacao }}
                </p>
            </div>
            @endif

        </div>

    </div>

</div>
@endsection