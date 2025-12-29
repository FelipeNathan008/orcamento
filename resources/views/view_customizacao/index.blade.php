@extends('layouts.app') {{-- Assumindo que você tem um layout principal chamado 'app' --}}

@section('title', 'Lista de Customizações')

@section('content')
{{-- Div principal que centraliza o conteúdo e ajusta a largura máxima --}}
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-2xl mt-10 mb-10 font-poppins"> {{-- Sombra mais pronunciada
        --}}

    {{-- Cabeçalho da Seção (Título e Botões) --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-gray-800 font-bai-jamjuree mb-4 sm:mb-0 flex items-center">
            {{-- Adicionado flex e items-center --}}
            Lista de Customizações
        </h1>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
            {{-- Botão Voltar (aparece apenas se houver um ID de detalhe na URL) --}}
            @if(request('search_detalhes_id'))
            <a href="{{ route('detalhes_orcamento.index', ['search_detalhe_cod' => request('search_detalhes_id')]) }}"
                class="inline-flex items-center justify-center px-4 py-2 border border-blue-300 shadow-md text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                Voltar com ID Detalhes
            </a>
            @endif

            @if(request('search_orcamento_id'))
            <a href="{{ route('detalhes_orcamento.index', ['search_orcamento_id' => request('search_orcamento_id')]) }}"
                class="inline-flex items-center justify-center px-4 py-2 border border-blue-300 shadow-md text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                Voltar com ID Orçamento
            </a>
            @endif

            <a href="{{ route('customizacao.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                style="background-color: #EA792D;">
                Criar Nova Customização
            </a>

        </div>
    </div>

    {{-- Exibe o nome e categoria do produto se vierem da URL (ao clicar em "Ver Customizações" na lista de detalhes)
        --}}
    @if(request('prod_nome_from_list') && request('prod_categoria_from_list'))
    <p class="text-lg text-gray-700 font-normal mb-4 -mt-4"> {{-- Nova tag
            <p> para exibir abaixo --}}
        ({{ request('prod_nome_from_list') }}, {{ request('prod_categoria_from_list') }})
    </p>
    @endif

    {{-- Botão "Layout Customizado" agora aparece se houver uma busca por ID do detalhe E customizações existirem --}}
    @if(request('search_detalhes_id') && !$customizacoes->isEmpty())
    <div class="flex justify-start mb-6">
        {{-- O botão agora aponta para a nova rota 'camisa.show_layout' --}}
        <a href="{{ route('camisa.show_layout', $customizacoes->first()->id_customizacao) }}"
            class="inline-flex items-center justify-center py-2 px-6 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
            Layout Customizado
        </a>
    </div>
    @endif

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Campos de busca separados --}}
    <form id="filtroForm" action="{{ route('customizacao.index') }}" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            {{-- Busca por ID do Orçamento --}}
            <div class="relative w-full">
                <input type="text" name="search_orcamento_id" id="search_orcamento_id"
                    value="{{ request('search_orcamento_id') }}" placeholder="Buscar por ID do Orçamento..."
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-gray-300 rounded-md outline-none
                                         hover:border-blue-400 focus:border-blue-500 disabled:border-gray-300 disabled:text-gray-300 disabled:bg-gray-50 text-gray-800"> {{-- Bordas e foco mais suaves --}}
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-gray-500" viewBox="0 0 20 20"
                    fill="currentColor"> {{-- Cor do ícone mais suave --}}
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>

            {{-- Busca por Nome do Cliente --}}
            <div class="relative w-full">
                <input type="text" name="search_cliente_nome" id="search_cliente_nome"
                    value="{{ request('search_cliente_nome') }}" placeholder="Buscar por Nome do Cliente..."
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-gray-300 rounded-md outline-none
                                         hover:border-blue-400 focus:border-blue-500 disabled:border-gray-300 disabled:text-gray-300 disabled:bg-gray-50 text-gray-800"> {{-- Bordas e foco mais suaves --}}
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-gray-500" viewBox="0 0 20 20"
                    fill="currentColor"> {{-- Cor do ícone mais suave --}}
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>

            {{-- Busca por ID do Detalhe do Orçamento --}}
            <div class="relative w-full">
                <input type="text" name="search_detalhes_id" id="search_detalhes_id"
                    value="{{ request('search_detalhes_id') }}" placeholder="Buscar por ID do Detalhe..."
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-gray-300 rounded-md outline-none
                                         hover:border-blue-400 focus:border-blue-500 disabled:border-gray-300 disabled:text-gray-300 disabled:bg-gray-50 text-gray-800"> {{-- Bordas e foco mais suaves
                    --}}
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-gray-500" viewBox="0 0 20 20"
                    fill="currentColor"> {{-- Cor do ícone mais suave --}}
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        {{-- Botão de busca --}}
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 mt-4">
            <button type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                Buscar
            </button>
            @if(request('search_orcamento_id') || request('search_cliente_nome') || request('search_detalhes_id'))
            <a href="{{ route('customizacao.index') }}"
                class="inline-flex items-center justify-center py-3 px-8 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                {{-- Cores neutras para o botão limpar filtro --}}
                Limpar Filtro
            </a>
            @endif
        </div>
        {{-- COMENTÁRIO IMPORTANTE: Para busca exata no ID do Detalhe --}}
        {{-- No seu controlador CustomizacaoController, ao lidar com 'search_detalhes_id',
            modifique a query para usar um WHERE direto para busca exata, como:
            ->when($request->search_detalhes_id, function ($query, $detalheId) {
            $query->where('detalhes_orcamento_id_det', $detalheId);
            })
            Ao invés de:
            ->when($request->search_detalhes_id, function ($query, $detalheId) {
            $query->where('detalhes_orcamento_id_det', 'LIKE', '%' . $detalheId . '%');
            })
            --}}
    </form>

    {{-- O bloco script JS do debounce foi removido, pois agora temos um botão de submissão explícito. --}}

    @if ($customizacoes->isEmpty())
    <p class="text-gray-600 text-center py-8" id="noResultsMessage">Nenhuma customização encontrada.</p>
    @else
    <div class="w-full rounded-lg shadow-xl mb-4 overflow-x-auto border border-gray-200">
        <table class="min-w-full w-full divide-y divide-gray-200">
            <thead class="bg-gray-800"> {{-- Cabeçalho da tabela mais escuro para contraste --}}
                <tr>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins rounded-tl-lg">
                        Orçamento Ref.
                    </th>
                    {{-- NOVA COLUNA: ID Detalhe --}}
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        ID Detalhe
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Cliente
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Tipo
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Local
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Formatação
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Imagem
                    </th>
                    <th scope="col"
                        class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins rounded-tr-lg">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($customizacoes as $customizacao)
                <tr class="hover:bg-blue-50 transition duration-150"> {{-- Efeito hover mais visível --}}
                    <td class="px-4 py-4 text-sm font-medium text-gray-900 font-poppins">
                        {{-- Exibindo o ID do Orçamento associado ao detalhe --}}
                        {{ $customizacao->detalhesOrcamento->orcamento->id_orcamento ?? 'N/A' }}
                    </td>
                    {{-- NOVA CÉLULA: ID Detalhe --}}
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $customizacao->detalhes_orcamento_id_det ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{-- Exibindo o nome do Cliente associado ao Orçamento --}}
                        {{ $customizacao->detalhesOrcamento->orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $customizacao->cust_tipo }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $customizacao->cust_local }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $customizacao->cust_formatacao }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        @if ($customizacao->cust_imagem)
                        <img src="data:image/jpeg;base64,{{ base64_encode($customizacao->cust_imagem) }}" alt="Imagem"
                            class="w-16 h-16 object-cover rounded-md shadow-sm">
                        @else
                        <span class="text-gray-500">Sem imagem</span>
                        @endif
                    </td>
                    <td class="px-2 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-1 sm:space-x-2">

                            <a href="{{ route('customizacao.show', $customizacao->id_customizacao) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                Ver
                            </a>

                            <a href="{{ route('customizacao.edit', $customizacao->id_customizacao) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-150 ease-in-out">
                                {{-- Amarelo mais vibrante --}}
                                Editar
                            </a>
                            {{-- NOVO BOTÃO LAYOUT ATUALIZADO --}}
                            <a href="{{ route('customizacao.index', [
                                            'search_detalhes_id' => $customizacao->detalhes_orcamento_id_det,
                                            // ADICIONADO: Passa o ID do Orçamento associado
                                            'search_orcamento_id' => $customizacao->detalhesOrcamento->orcamento->id_orcamento ?? null,
                                            'prod_nome_from_list' => optional($customizacao->detalhesOrcamento->produto)->prod_nome ?? 'Nome do Produto Desconhecido',
                                            'prod_categoria_from_list' => optional($customizacao->detalhesOrcamento->produto)->prod_categoria ?? 'Categoria Desconhecida'
                                        ]) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out">
                                Layout
                            </a>

                            <form action="{{ route('customizacao.destroy', $customizacao->id_customizacao) }}"
                                method="POST" class="inline-block"
                                onsubmit="return confirm('Tem certeza que deseja excluir esta customização?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p class="text-gray-600 text-center py-8 hidden" id="noResultsCustomizacaoMessage">Nenhuma customização
            encontrada com esse termo de busca.</p>
    </div>
    @endif
</div>
@endsection