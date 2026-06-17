@extends('layouts.app')

@section('title', 'Customizações')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">

        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree">
            Customizações do Produto
        </h1>

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">

            <a href="{{ route('detalhes_orcamento.index', ['orcamento_id' => $orcamento->id_orcamento]) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR
            </a>

            <a href="{{ route('customizacao.create', ['detalhe_id' => $detalhe->id_det]) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 transition duration-150 ease-in-out"
                style="background-color:#EA792D;">
                Nova Customização
            </a>

        </div>
    </div>

    @if(isset($orcamento))
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações do Produto
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
            <div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Cód. Fábrica</p>
                        <p class="font-semibold">
                            {{ $detalhe->orcamento->orc_cod_fabrica }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600">Cód. Interno</p>
                        <p class="font-semibold">
                            {{ $detalhe->orcamento->orc_cod_interno }}
                        </p>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold text-gray-900">
                    {{ $cliente->clie_orc_nome ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Produto</p>
                <p class="font-semibold">
                    {{ $detalhe->produto->prod_cod ?? 'N/A' }} -
                    {{ $detalhe->produto->prod_nome ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Categoria</p>
                <p class="font-semibold text-gray-900">
                    {{ $detalhe->produto->prod_categoria ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Cor / Tamanho</p>
                <p class="font-semibold">
                    {{ $detalhe->produto->prod_cor ?? 'N/A' }} -
                    {{ $detalhe->det_tamanho ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Características</p>
                <p class="font-semibold">
                    {{ $detalhe->det_caract ?? 'N/A' }}
                </p>
            </div>

        </div>

    </div>
    @endif

    @php
    $totalItem = $detalhe->det_quantidade * $detalhe->det_valor_unit;

    $totalCustomizacoes = 0;

    foreach ($detalhe->customizacoes as $customizacao) {
    $totalCustomizacoes += $customizacao->cust_valor;
    }

    $totalGeral = $totalItem + $totalCustomizacoes;
    @endphp
    
    <div id="image-warning-message" class="mt-2 text-sm text-yellow-600">
        <i class="fas fa-exclamation-triangle mr-1"></i>Os valores abaixo são conforme o produto selecionado.
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
            <p class="text-gray-600 text-sm">
                Total dos Itens
            </p>

            <p class="text-lg font-bold text-gray-800">
                R$ {{ number_format($totalItem, 2, ',', '.') }}
            </p>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
            <p class="text-gray-600 text-sm">
                Total das Customizações
            </p>

            <p class="text-lg font-bold text-gray-800">
                R$ {{ number_format($totalCustomizacoes, 2, ',', '.') }}
            </p>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 shadow-sm">
            <p class="text-orange-700 text-sm">
                Total Geral
            </p>

            <p class="text-xl font-bold text-orange-700">
                R$ {{ number_format($totalGeral, 2, ',', '.') }}
            </p>
        </div>

    </div>
    <x-alert-flash />

    <form method="GET" action="{{ route('customizacao.index') }}" class="mb-6">

        <input type="hidden"
            name="id_det"
            value="{{ $detalhe->id_det }}">

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Tipo --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tipo
                    </label>

                    <select
                        name="tipo"
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">

                        <option value="">Todos</option>

                        @foreach($tipos as $tipo)
                        <option
                            value="{{ $tipo }}"
                            {{ request('tipo') == $tipo ? 'selected' : '' }}>
                            {{ $tipo }}
                        </option>
                        @endforeach

                    </select>
                </div>

                {{-- Local --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Local
                    </label>

                    <select
                        name="local"
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">

                        <option value="">Todos</option>

                        @foreach($locais as $local)
                        <option
                            value="{{ $local }}"
                            {{ request('local') == $local ? 'selected' : '' }}>
                            {{ $local }}
                        </option>
                        @endforeach

                    </select>
                </div>

                {{-- Posição --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Posição
                    </label>

                    <select
                        name="posicao"
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">

                        <option value="">Todas</option>

                        @foreach($posicoes as $posicao)
                        <option
                            value="{{ $posicao }}"
                            {{ request('posicao') == $posicao ? 'selected' : '' }}>
                            {{ $posicao }}
                        </option>
                        @endforeach

                    </select>
                </div>

                {{-- Formatação --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Formatação
                    </label>

                    <select
                        name="formatacao"
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">

                        <option value="">Todas</option>

                        @foreach($formatacoes as $formatacao)
                        <option
                            value="{{ $formatacao }}"
                            {{ request('formatacao') == $formatacao ? 'selected' : '' }}>
                            {{ $formatacao }}
                        </option>
                        @endforeach

                    </select>
                </div>

                {{-- Buscar --}}
                <div class="flex items-end">
                    <button
                        type="submit"
                        class="w-full h-10 text-white rounded-md"
                        style="background-color:#EA792D;">
                        Buscar
                    </button>
                </div>

                {{-- Limpar --}}
                <div class="flex items-end">
                    <a
                        href="{{ route('customizacao.index', ['id_det' => $detalhe->id_det]) }}"
                        class="w-full h-10 bg-gray-300 rounded-md text-gray-800 flex items-center justify-center hover:bg-gray-400 transition">
                        Limpar
                    </a>
                </div>

            </div>

        </div>

    </form>

    {{-- TABELA --}}

    @if ($customizacoes->isEmpty())

    @if(
    request('tipo') ||
    request('local') ||
    request('posicao') ||
    request('formatacao')
    )

    <div class="text-center py-8">
        <p class="text-gray-600">
            Nenhuma customização encontrada para os filtros informados.
        </p>

        <a href="{{ route('customizacao.index', [
                    'id_det' => $detalhe->id_det
                ]) }}"
            class="inline-block mt-3 text-orange-600 hover:text-orange-700 font-medium">
            Limpar filtros
        </a>
    </div>

    @else

    <p class="text-gray-600 text-center py-8">
        Nenhuma customização cadastrada para este produto.
    </p>

    @endif

    @else


    <div class="w-full rounded-lg shadow-xl overflow-x-auto border border-gray-200">

        <table class="min-w-full w-full divide-y divide-gray-200">

            <thead class="bg-gray-800">
                <tr>

                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">
                        Tipo
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">
                        Local
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">
                        Posição
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">
                        Formatação
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">
                        Valor
                    </th>


                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">
                        Imagem
                    </th>

                    <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase">
                        Ações
                    </th>

                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">

                @foreach($customizacoes as $customizacao)

                <tr class="hover:bg-blue-50 transition">

                    <td class="px-4 py-4 text-sm text-gray-800">
                        {{ $customizacao->cust_tipo }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-800">
                        {{ $customizacao->cust_local }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-800">
                        {{ $customizacao->cust_posicao }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-800">
                        {{ $customizacao->cust_formatacao }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-800">
                        R$ {{ number_format($customizacao->cust_valor, 2, ',', '.') }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-800">
                        @if($customizacao->cust_imagem)
                        <img src="{{ asset('images_customizacoes/' . $customizacao->cust_imagem) }}"
                            class="w-16 h-16 object-cover rounded shadow">

                        @else
                        <span class="text-gray-500">Sem imagem</span>
                        @endif

                    </td>

                    <td class="px-4 py-4 text-center">

                        <div class="flex justify-center gap-2">

                            <a href="{{ route('customizacao.camisa', ['id' => $customizacao->id_customizacao]) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out">
                                Layout
                            </a>


                            <a href="{{ route('customizacao.show', $customizacao->id_customizacao) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Ver
                            </a>

                            <a href="{{ route('customizacao.edit', $customizacao->id_customizacao) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                                Editar
                            </a>
                            <form action="{{ route('customizacao.destroy', $customizacao->id_customizacao) }}" method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Tem certeza que deseja excluir esta customização?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-cancel-bg hover:bg-button-cancel-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-cancel-bg transition duration-150 ease-in-out">
                                    Excluir
                                </button>
                            </form>

                        </div>

                    </td>

                </tr>

                @endforeach

            </tbody>
        </table>

    </div>

    <div class="mt-4">
        <x-pagination-compact :paginator="$customizacoes" />
    </div>

    @endif
</div>

@endsection