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

    {{-- INFORMAÇÕES DO DETALHE --}}

    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações do Produto
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-gray-700">

            <div>
                <span class="font-semibold">Orçamento:</span>
                {{ $orcamento->id_orcamento }}
            </div>

            <div>
                <span class="font-semibold">Cliente:</span>
                {{ $cliente->clie_orc_nome ?? 'N/A' }}
            </div>

            <div>
                <span class="font-semibold">Produto:</span>
                {{ $detalhe->produto->prod_nome ?? 'N/A' }}
            </div>

            <div>
                <span class="font-semibold">Categoria:</span>
                {{ $detalhe->produto->prod_categoria ?? 'N/A' }}
            </div>


        </div>

    </div>


    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4">
        {{ session('success') }}
    </div>
    @endif


    {{-- TABELA --}}

    @if($customizacoes->isEmpty())

    <p class="text-gray-600 text-center py-10">
        Nenhuma customização cadastrada para este produto.
    </p>

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
                        @if($customizacao->cust_imagem)
                        <img
                            src="data:image/jpeg;base64,{{ base64_encode($customizacao->cust_imagem) }}"
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

    @endif

</div>

@endsection