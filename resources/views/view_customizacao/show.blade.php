@extends('layouts.app')

@section('content')
@php
$orcamentoBloqueado = in_array(
strtolower(trim($customizacao->detalhesOrcamento->orcamento->orc_status)),
['aprovado', 'finalizado', 'rejeitado']
);
@endphp
<div class="container mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">

        <h1 class="text-3xl font-bold text-gray-800">
            Detalhes da Customização
        </h1>

        <div class="flex space-x-3">

            @if(!$orcamentoBloqueado)
            <a href="{{ route('customizacao.edit', $customizacao->id_customizacao) }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Editar Customização
            </a>
            @endif
            <a href="{{ route('customizacao.index', ['id_det' => $customizacao->detalhes_orcamento_id_det]) }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Voltar para a Lista
            </a>

        </div>

    </div>

    <x-alert-flash />

    <div class="bg-white shadow-xl rounded-lg p-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-gray-600">ID</p>
                        <p class="font-semibold">
                            {{ $customizacao->detalhesOrcamento->orcamento->id_orcamento }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600">Cód. Fábrica</p>
                        <p class="font-semibold">
                            {{ $customizacao->detalhesOrcamento->orcamento->orc_cod_fabrica ?: 'N/D' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600">Cód. Interno</p>
                        <p class="font-semibold">
                            {{ $customizacao->detalhesOrcamento->orcamento->orc_cod_interno ?: 'N/D' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Cliente:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $customizacao->detalhesOrcamento->orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}
                </p>
            </div>


            <div class="mb-4">
                <p class="text-gray-600 text-sm">Tipo:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $customizacao->cust_tipo }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Local:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $customizacao->cust_local }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Posição:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $customizacao->cust_posicao }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Tamanho:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $customizacao->cust_tamanho }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Formatação:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $customizacao->cust_formatacao }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Valor:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    R$ {{ number_format($customizacao->cust_valor, 2, ',', '.') }}
                </p>
            </div>


            <div class="md:col-span-2 mb-4">

                <p class="text-gray-600 text-sm">Descrição:</p>

                <p class="text-gray-900 text-lg font-semibold">
                    {{ $customizacao->cust_descricao ?? 'Sem descrição' }}
                </p>

            </div>


            <div class="md:col-span-2 mb-4">

                <p class="text-gray-600 text-sm mb-2">
                    Imagem da Customização:
                </p>

                @if (!empty($customizacao->cust_imagem))

                <div class="mt-2">
                    <img
                        src="{{ asset('images_customizacoes/' . $customizacao->cust_imagem) }}"
                        alt="Imagem da Customização"
                        class="max-w-md max-h-96 object-contain rounded-md shadow-md border border-gray-200"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">

                    <p class="text-red-500 text-sm hidden">
                        Não foi possível carregar a imagem.
                    </p>
                </div>

                @else

                <p class="text-gray-500 text-lg">
                    Sem imagem
                </p>

                @endif

            </div>


            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Criado em:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $customizacao->created_at->format('d/m/Y H:i') }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Última Atualização:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $customizacao->updated_at->format('d/m/Y H:i') }}
                </p>
            </div>

        </div>

    </div>

</div>

@endsection