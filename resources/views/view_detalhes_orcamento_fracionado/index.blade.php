@extends('layouts.app_financeiro')

@section('title', 'Detalhes do Orçamento Fracionado')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- CABEÇALHO --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree">
            Detalhes do Fracionado #{{ $orcamentoFracionado->orc_fracao }}
        </h1>
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div>
                <a href="{{ route('orcamento.fracionado.index', ['id' => $orcamento->id_orcamento]) }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                    VOLTAR
                </a>
            </div>
            <a href="{{ route('detalhes_orcamento_fracionado.create', $orcamentoFracionado->id_orcamento_fracionado) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                style="background-color: #EA792D;">
                Novo Detalhe
            </a>
        </div>
    </div>

    {{-- INFORMAÇÕES DO ORÇAMENTO --}}
    @if(isset($orcamento))
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">
        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações do Orçamento
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">

            <div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-gray-600">ID</p>
                        <p class="font-semibold">
                            {{ $orcamento->id_orcamento }}
                        </p>
                    </div>


                    <div>
                        <p class="text-gray-600">Cód. Fábrica</p>
                        <p class="font-semibold">
                            {{ $orcamento->orc_cod_fabrica ?: 'N/D' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600">Cód. Interno</p>
                        <p class="font-semibold">
                            {{ $orcamento->orc_cod_interno ?: 'N/D' }}
                        </p>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold text-gray-900">
                    {{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-gray-600">Fração</p>
                <p class="font-semibold text-gray-900">
                    #{{ $orcamentoFracionado->orc_fracao }}
                </p>
            </div>
            <div>
                <p class="text-gray-600">Status</p>
                <p class="font-semibold text-gray-900">
                    {{ ucfirst($orcamentoFracionado->orc_status) }}
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- TOTAIS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
            <p class="text-gray-600 text-sm">Total dos Itens</p>
            <p class="text-lg font-bold text-gray-800">
                R$ {{ number_format($totalDetalhes, 2, ',', '.') }}
            </p>
        </div>
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
            <p class="text-gray-600 text-sm">Total das Customizações</p>
            <p class="text-lg font-bold text-gray-800">
                R$ {{ number_format($totalCustomizacoes, 2, ',', '.') }}
            </p>
        </div>
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 shadow-sm">
            <p class="text-orange-700 text-sm">Total Geral</p>
            <p class="text-xl font-bold text-orange-700">
                R$ {{ number_format($totalGeral, 2, ',', '.') }}
            </p>
        </div>
    </div>

    <x-alert-flash />

    {{-- FILTROS --}}
    <form method="GET"
        action="{{ route('detalhes_orcamento_fracionado.index', $orcamentoFracionado->id_orcamento_fracionado) }}"
        class="mb-6">
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Produto
                    </label>
                    <input
                        type="text"
                        name="produto"
                        value="{{ request('produto') }}"
                        placeholder="Digite o nome..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Categoria
                    </label>
                    <input
                        type="text"
                        name="categoria"
                        value="{{ request('categoria') }}"
                        placeholder="Digite a categoria..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Código Ref.
                    </label>
                    <input
                        type="text"
                        name="cod_ref"
                        value="{{ request('cod_ref') }}"
                        placeholder="Digite o código..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Família
                    </label>
                    <select
                        name="familia"
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
                        <option value="">Todas</option>
                        @foreach($familias as $familia)
                        <option
                            value="{{ $familia }}"
                            {{ request('familia') == $familia ? 'selected' : '' }}>
                            {{ $familia }}
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
                        href="{{ route('detalhes_orcamento_fracionado.index', $orcamentoFracionado->id_orcamento_fracionado) }}"
                        class="w-full h-10 bg-gray-300 rounded-md text-gray-800 flex items-center justify-center hover:bg-gray-400 transition">
                        Limpar
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- LISTAGEM --}}
    @if ($detalhesOrcamento->isEmpty())
    @if(
    request('produto') ||
    request('categoria') ||
    request('cod_ref') ||
    request('familia')
    )
    <div class="text-center py-8">
        <p class="text-gray-600">
            Nenhum detalhe encontrado para os filtros informados.
        </p>
        <a href="{{ route('detalhes_orcamento_fracionado.index', $orcamentoFracionado->id_orcamento_fracionado) }}"
            class="inline-block mt-3 text-orange-600 hover:text-orange-700 font-medium">
            Limpar filtros
        </a>
    </div>
    @else
    <p class="text-gray-600 text-center py-8">
        Nenhum detalhe de orçamento fracionado cadastrado.
    </p>
    @endif
    @else
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Produto
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Cód.
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Categoria
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Tam.
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-right text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Qtd.
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-right text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Valor Unit.
                    </th>
                    <th scope="col"
                        class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody id="detalhesTableBody" class="bg-white divide-y divide-gray-200">
                @foreach ($detalhesOrcamento as $detalhe)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $detalhe->produto->prod_nome ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $detalhe->det_cod }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $detalhe->det_categoria }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $detalhe->det_tamanho ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins text-right">
                        {{ $detalhe->det_quantidade }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins text-right">
                        R$ {{ number_format($detalhe->det_valor_unit, 2, ',', '.') }}
                    </td>

                    <td class="px-2 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                            {{-- Botão "Customizações" com badge --}}
                            <a href="{{ route('customizacao_fracionado.index', ['id_det_fracionado' => $detalhe->id_det_fracionado]) }}"
                                class="relative inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                Customizações
                                @if($detalhe->customizacoes->count() > 0)
                                <span class="absolute -top-2 -right-2 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-orange-500 rounded-full">
                                    {{ $detalhe->customizacoes->count() }}
                                </span>
                                @endif
                            </a>

                            {{-- Botão "Ver" --}}
                            <a href="{{ route('detalhes_orcamento_fracionado.show',$detalhe->id_det_fracionado) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Ver
                            </a>

                            {{-- Botão "Editar" 
                            <a href="{{ route('detalhes_orcamento_fracionado.edit', $detalhe->id_det_fracionado) }}"
                            class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                            Editar
                            </a> --}}

                            {{-- Botão "Excluir" --}}
                            <form action="{{ route('detalhes_orcamento_fracionado.destroy', $detalhe->id_det_fracionado) }}"
                                method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Tem certeza que deseja excluir este detalhe de orçamento fracionado?');">
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
        <x-pagination-compact :paginator="$detalhesOrcamento" />
    </div>
    @endif
</div>
@endsection