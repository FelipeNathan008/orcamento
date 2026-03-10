@extends('layouts.app')

@section('title', 'Lista de Detalhes de Orçamentos')

@section('content')
{{-- Div principal que centraliza o conteúdo e ajusta a largura máxima --}}
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">

        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree">
            Detalhes de Orçamentos Cadastrados
        </h1>
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">

            <div>
                <a href="{{ route('orcamento.index', ['cliente_orcamento_id' => $orcamento->cliente_orcamento_id_co]) }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                    VOLTAR
                </a>
            </div>
            <a href="{{ route('detalhes_orcamento.create', ['orcamento_id' => $orcamento->id_orcamento]) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                style="background-color: #EA792D;">
                Novo Detalhe
            </a>
        </div>

    </div>
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif


    @if(isset($orcamento))
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações do Orçamento
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">

            <div>
                <p class="text-gray-600">ID</p>
                <p class="font-semibold text-gray-900">
                    {{ $orcamento->id_orcamento }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold text-gray-900">
                    {{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Data Início</p>
                <p class="font-semibold text-gray-900">
                    {{ $orcamento->orc_data_inicio->format('d/m/Y') }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Status</p>
                <p class="font-semibold text-gray-900">
                    {{ ucfirst($orcamento->orc_status) }}
                </p>
            </div>

        </div>
    </div>
    @endif

    <div class="mb-6">
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                <!-- Código -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Filtrar por Código
                    </label>
                    <input type="text"
                        id="filtroCodigo"
                        placeholder="Digite o código"
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Categoria -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Filtrar por Categoria
                    </label>
                    <select id="filtroCategoria"
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500">
                        <option value="">Todas as Categorias</option>
                    </select>
                </div>

                <!-- Limpar -->
                <div class="flex md:justify-end items-end">
                    <button type="button"
                        id="btnLimparFiltro"
                        class="inline-flex items-center px-4 py-2 h-10 border border-transparent text-sm font-medium rounded-md shadow-sm text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition duration-150 ease-in-out">
                        Limpar Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if ($detalhesOrcamento->isEmpty())
    <p class="text-gray-600 text-center py-8">Nenhum detalhe de orçamento encontrado.</p>
    @else
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>

                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Cliente
                    </th>
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
                        {{ $detalhe->orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}
                    </td>
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
                            {{-- Botão "Customizar" (NOVA COR) --}}
                            <a href="{{ route('customizacao.create', ['detalhe_id' => $detalhe->id_det]) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out">
                                Customizar
                            </a>

                            {{-- Botão "Customizações" com parâmetros adicionais --}}
                            <a href="{{ route('customizacao.index', ['id_det' => $detalhe->id_det]) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                Customizações
                            </a>

                            {{-- Botão "Ver" --}}
                            <a href="{{ route('detalhes_orcamento.show', $detalhe->id_det) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Ver
                            </a>

                            {{-- Botão "Editar" --}}
                            <a href="{{ route('detalhes_orcamento.edit', $detalhe->id_det) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                                Editar
                            </a>
                            {{-- Botão "Excluir" --}}
                            <form action="{{ route('detalhes_orcamento.destroy', $detalhe->id_det) }}" method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Tem certeza que deseja excluir este detalhe de orçamento?');">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const filtroCodigo = document.getElementById('filtroCodigo');
        const filtroCategoria = document.getElementById('filtroCategoria');
        const btnLimpar = document.getElementById('btnLimparFiltro');
        const tbody = document.getElementById('detalhesTableBody');
        const noMessage = document.getElementById('noOrcamentosMessage');

        if (!tbody) return;

        const linhas = tbody.querySelectorAll('tr');

        // ==============================
        // POPULAR SELECT DE CATEGORIA
        // ==============================
        let categorias = new Set();

        linhas.forEach(linha => {
            const categoria = linha.children[3].textContent.trim();
            if (categoria !== '') {
                categorias.add(categoria);
            }
        });

        categorias.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.trim().toLowerCase();
            option.textContent = cat.trim();
            filtroCategoria.appendChild(option);
        });

        // ==============================
        // FUNÇÃO DE FILTRO
        // ==============================
        function aplicarFiltro() {

            const codigo = filtroCodigo.value.toLowerCase().trim();
            const categoriaSelecionada = filtroCategoria.value.toLowerCase().trim();

            let algumaVisivel = false;

            linhas.forEach(linha => {

                const codigoLinha = linha.children[2].textContent.toLowerCase().trim();
                const categoriaLinha = linha.children[3].textContent.toLowerCase().trim();

                const matchCodigo = codigo === '' || codigoLinha.includes(codigo);
                const matchCategoria = categoriaSelecionada === '' || categoriaLinha === categoriaSelecionada;

                if (matchCodigo && matchCategoria) {
                    linha.style.display = '';
                    algumaVisivel = true;
                } else {
                    linha.style.display = 'none';
                }

            });

            // Mostrar mensagem se nada encontrado
            if (noMessage) {
                if (!algumaVisivel) {
                    noMessage.classList.remove('hidden');
                } else {
                    noMessage.classList.add('hidden');
                }
            }
        }

        // ==============================
        // FILTRO AUTOMÁTICO
        // ==============================
        filtroCodigo.addEventListener('input', aplicarFiltro);
        filtroCategoria.addEventListener('change', aplicarFiltro);

        // ==============================
        // LIMPAR FILTRO
        // ==============================
        btnLimpar.addEventListener('click', function() {
            filtroCodigo.value = '';
            filtroCategoria.value = '';
            aplicarFiltro();
        });

    });
</script>

@endsection