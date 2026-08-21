@extends('layouts.app')

@section('title', 'Detalhes do Item do Orçamento Fracionado')

@section('content')

<div class="container mx-auto px-4 py-8">

    {{-- Cabeçalho --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Detalhes do Item
            </h1>

            <p class="text-gray-500 mt-1">
                Orçamento Fracionado #{{ $orcamentoFracionado->id_orcamento_fracionado }}
            </p>
        </div>

        <div class="flex flex-wrap gap-3">

            {{-- Voltar para a lista do fracionado --}}
            <a href="{{ route(
                'detalhes_orcamento_fracionado.index',
                $orcamentoFracionado->id_orcamento_fracionado
            ) }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">

                Voltar para a Lista

            </a>

        </div>

    </div>


    {{-- Informações do orçamento --}}
    <div class="bg-white shadow-xl rounded-lg p-8 mb-6">

        <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-3">
            Informações do Orçamento
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Código de fábrica --}}
            <div>
                <p class="text-gray-600 text-sm">
                    Cód. Fábrica
                </p>

                <p class="text-gray-900 text-lg font-semibold">
                    {{ $orcamento->orc_cod_fabrica ?? 'N/A' }}
                </p>
            </div>


            {{-- Código interno --}}
            <div>
                <p class="text-gray-600 text-sm">
                    Cód. Interno
                </p>

                <p class="text-gray-900 text-lg font-semibold">
                    {{ $orcamento->orc_cod_interno ?? 'N/A' }}
                </p>
            </div>


            {{-- ID do orçamento fracionado --}}
            <div>
                <p class="text-gray-600 text-sm">
                    Orçamento Fracionado
                </p>

                <p class="text-gray-900 text-lg font-semibold">
                    #{{ $orcamentoFracionado->id_orcamento_fracionado }}
                </p>
            </div>


            {{-- Cliente --}}
            <div class="md:col-span-3">

                <p class="text-gray-600 text-sm">
                    Cliente
                </p>

                <p class="text-gray-900 text-lg font-semibold">
                    {{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}
                </p>

            </div>

        </div>

    </div>


    {{-- Detalhes do produto --}}
    <div class="bg-white shadow-xl rounded-lg p-8 mb-6">

        <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-3">
            Detalhes do Produto
        </h2>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


            {{-- Produto --}}
            <div class="md:col-span-2">

                <p class="text-gray-600 text-sm">
                    Produto
                </p>

                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->produto->prod_nome ?? 'N/A' }}
                </p>

            </div>


            {{-- Código --}}
            <div>

                <p class="text-gray-600 text-sm">
                    Código
                </p>

                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_cod ?? 'N/A' }}
                </p>

            </div>


            {{-- Categoria --}}
            <div>

                <p class="text-gray-600 text-sm">
                    Categoria
                </p>

                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_categoria ?? 'N/A' }}
                </p>

            </div>


            {{-- Modelo --}}
            <div>

                <p class="text-gray-600 text-sm">
                    Modelo
                </p>

                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_modelo ?? 'N/A' }}
                </p>

            </div>


            {{-- Cor --}}
            <div>

                <p class="text-gray-600 text-sm">
                    Cor
                </p>

                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_cor ?? 'N/A' }}
                </p>

            </div>


            {{-- Gênero --}}
            <div>

                <p class="text-gray-600 text-sm">
                    Gênero
                </p>

                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_genero ?? 'N/A' }}
                </p>

            </div>


            {{-- Tamanho --}}
            <div>

                <p class="text-gray-600 text-sm">
                    Tamanho
                </p>

                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_tamanho ?? 'N/A' }}
                </p>

            </div>


            {{-- Quantidade --}}
            <div>

                <p class="text-gray-600 text-sm">
                    Quantidade
                </p>

                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_quantidade }}
                </p>

            </div>


            {{-- Valor unitário --}}
            <div>

                <p class="text-gray-600 text-sm">
                    Valor Unitário
                </p>

                <p class="text-gray-900 text-lg font-semibold">
                    R$
                    {{ number_format(
                        $detalheOrcamento->det_valor_unit,
                        2,
                        ',',
                        '.'
                    ) }}
                </p>

            </div>


            {{-- Total do item --}}
            <div>

                <p class="text-gray-600 text-sm">
                    Total do Item
                </p>

                <p class="text-green-700 text-xl font-bold">

                    R$
                    {{ number_format(
                        $detalheOrcamento->det_quantidade *
                        $detalheOrcamento->det_valor_unit,
                        2,
                        ',',
                        '.'
                    ) }}

                </p>

            </div>


            {{-- Características --}}
            <div class="md:col-span-2">

                <p class="text-gray-600 text-sm">
                    Características
                </p>

                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalheOrcamento->det_caract ?? 'N/A' }}
                </p>

            </div>


            {{-- Observação --}}
            @if($detalheOrcamento->det_observacao)

                <div class="md:col-span-2">

                    <p class="text-gray-600 text-sm">
                        Observação
                    </p>

                    <p class="text-gray-900 text-lg font-semibold">
                        {{ $detalheOrcamento->det_observacao }}
                    </p>

                </div>

            @endif


            {{-- Anotação --}}
            @if($detalheOrcamento->det_anotacao)

                <div class="md:col-span-2">

                    <p class="text-gray-600 text-sm">
                        Anotação
                    </p>

                    <p class="text-gray-900 text-lg font-semibold">
                        {{ $detalheOrcamento->det_anotacao }}
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- Customizações --}}
    <div class="bg-white shadow-xl rounded-lg p-8">

        <div class="flex justify-between items-center mb-6 border-b pb-3">

            <h2 class="text-xl font-bold text-gray-800">
                Customizações
            </h2>

            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-semibold">
                {{ $detalheOrcamento->customizacoes->count() }}
                {{ $detalheOrcamento->customizacoes->count() == 1 ? 'customização' : 'customizações' }}
            </span>

        </div>


        @if($detalheOrcamento->customizacoes->count() > 0)

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                Tipo
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                Local
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                Posição
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                Tamanho
                            </th>

                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                Descrição
                            </th>

                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">
                                Valor
                            </th>

                        </tr>

                    </thead>


                    <tbody class="bg-white divide-y divide-gray-200">

                        @foreach($detalheOrcamento->customizacoes as $customizacao)

                            <tr class="hover:bg-gray-50">

                                <td class="px-4 py-4 text-sm text-gray-900">
                                    {{ $customizacao->cust_tipo ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-4 text-sm text-gray-900">
                                    {{ $customizacao->cust_local ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-4 text-sm text-gray-900">
                                    {{ $customizacao->cust_posicao ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-4 text-sm text-gray-900">
                                    {{ $customizacao->cust_tamanho ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-4 text-sm text-gray-900">
                                    {{ $customizacao->cust_descricao ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-4 text-sm text-gray-900 text-right font-semibold">

                                    R$
                                    {{ number_format(
                                        $customizacao->cust_valor ?? 0,
                                        2,
                                        ',',
                                        '.'
                                    ) }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Total das customizações --}}
            <div class="mt-6 flex justify-end">

                <div class="bg-gray-50 rounded-lg px-6 py-4">

                    <p class="text-gray-600 text-sm">
                        Total das Customizações
                    </p>

                    <p class="text-gray-900 text-xl font-bold">

                        R$
                        {{ number_format(
                            $detalheOrcamento->customizacoes->sum('cust_valor'),
                            2,
                            ',',
                            '.'
                        ) }}

                    </p>

                </div>

            </div>

        @else

            <div class="text-center py-8">

                <p class="text-gray-500">
                    Este item não possui customizações cadastradas.
                </p>

            </div>

        @endif

    </div>


</div>

@endsection