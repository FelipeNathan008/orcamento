@extends('layouts.app') {{-- Layout principal --}}

@section('title', 'Lista de Orçamentos') {{-- Título da Página --}}

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho com título e botões --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Orçamentos Cadastrados
        </h1>

        <div class="flex items-center gap-3">

            <a href="{{ route('cliente_orcamento.index') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR
            </a>

            <a href="{{ route('orcamento.create', ['cliente_orcamento_id' => $clienteSelecionado->id_co]) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                style="background-color: #EA792D;">
                Novo Orçamento
            </a>

        </div>

    </div>

    @if(request('cliente_orcamento_id') && isset($clienteSelecionado))
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
            <h2 class="text-lg font-bold text-orange-700">
                Informações do Cliente Selecionado
            </h2>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

            <div>
                <p class="text-gray-600">Nome</p>
                <p class="font-semibold text-gray-900">
                    {{ $clienteSelecionado->clie_orc_nome }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">E-mail</p>
                <p class="font-semibold text-gray-900">
                    {{ $clienteSelecionado->clie_orc_email }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Celular</p>
                <p class="font-semibold text-gray-900">
                    {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', preg_replace('/\D/', '', $clienteSelecionado->clie_orc_celular)) }}
                </p>
            </div>

        </div>
    </div>
    @endif

    {{-- Formulários de Busca e Filtro --}}
    <form method="GET" action="{{ route('orcamento.index') }}" id="filtroForm" class="mb-6">

        <input type="hidden" name="cliente_orcamento_id" value="{{ $clienteSelecionado->id_co }}">

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">

                {{-- Filtro por Status --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Filtrar por Status
                    </label>

                    <select name="status_query"
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Mostrar todos</option>
                        <option value="pendente" {{ request('status_query') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="para aprovacao" {{ request('status_query') == 'para aprovacao' ? 'selected' : '' }}>Para Aprovação</option>
                        <option value="aprovado" {{ request('status_query') == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                        <option value="finalizado" {{ request('status_query') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    </select>
                </div>

                {{-- Filtro por Vencimento --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Filtrar por Vencimento
                    </label>

                    <div class="flex flex-wrap gap-4 h-10 items-center">

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio"
                                name="filtro_vencimento"
                                value="ativos"
                                {{ request('filtro_vencimento', 'ativos') == 'ativos' ? 'checked' : '' }}
                                class="h-4 w-4 text-orange-600 border-gray-300">
                            <span class="text-sm text-gray-700">Ativos</span>
                        </label>

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio"
                                name="filtro_vencimento"
                                value="todos"
                                {{ request('filtro_vencimento') == 'todos' ? 'checked' : '' }}
                                class="h-4 w-4 text-orange-600 border-gray-300">
                            <span class="text-sm text-gray-700">Todos</span>
                        </label>

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio"
                                name="filtro_vencimento"
                                value="vencidos"
                                {{ request('filtro_vencimento') == 'vencidos' ? 'checked' : '' }}
                                class="h-4 w-4 text-orange-600 border-gray-300">
                            <span class="text-sm text-gray-700">Vencidos</span>
                        </label>

                    </div>
                </div>

                {{-- Botão Limpar Filtros --}}
                <div class="flex md:justify-end items-end">
                    <a href="{{ route('orcamento.index', ['cliente_orcamento_id' => $clienteSelecionado->id_co]) }}"
                        class="inline-flex items-center px-4 py-2 h-10 border border-transparent text-sm font-medium rounded-md shadow-sm text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition duration-150 ease-in-out">
                        Limpar Filtros
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- Mensagem "Nenhum orçamento" --}}
    <p class="text-gray-600 text-center py-8 @if (!$orcamentos->isEmpty()) hidden @endif" id="noOrcamentosMessage">
        Nenhum orçamento cadastrado ainda.
    </p>

    {{-- Tabela de Orçamentos --}}
    @if (!$orcamentos->isEmpty())
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto" id="orcamentoTableContainer">
        <table class="min-w-full w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        ID</th>
                    <th
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Cliente</th>
                    <th
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Data Início</th>
                    <th
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Data Fim</th>
                    <th
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Status</th>
                    <th
                        class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="orcamentoTableBody">
                @foreach ($orcamentos as $orcamento)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-4 py-4 text-sm font-medium text-gray-900 font-poppins">{{ $orcamento->id_orcamento }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'Cliente Não Encontrado' }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $orcamento->orc_data_inicio->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $orcamento->orc_data_fim->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        @php
                        $normalizedStatus = strtolower(str_replace(' ', '_', $orcamento->orc_status));
                        switch ($normalizedStatus) {
                        case 'pendente':
                        $statusClass = 'bg-yellow-400';
                        break;
                        case 'para_aprovacao':
                        $statusClass = 'bg-teal-600';
                        break;
                        case 'aprovado':
                        $statusClass = 'bg-green-400';
                        break;
                        case 'rejeitado':
                        $statusClass = 'bg-orange-400';
                        break;
                        case 'finalizado':
                        $statusClass = 'bg-gray-400';
                        break;
                        default:
                        $statusClass = 'bg-indigo-400';
                        break;
                        }
                        @endphp
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight text-gray-900">
                            <span aria-hidden="true"
                                class="absolute inset-0 opacity-50 rounded-full {{ $statusClass }}"></span>
                            <span class="relative">{{ ucfirst($orcamento->orc_status) }}</span>
                        </span>
                    </td>
                    <td class="px-2 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                            @if ($orcamento->orc_status !== 'finalizado')

                            {{-- Visualizar Detalhes --}}
                            <a href="{{ route('detalhes_orcamento.index', ['orcamento_id' => $orcamento->id_orcamento]) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                Visualizar Detalhes
                            </a>

                            @if (in_array($orcamento->orc_status, ['aprovado', 'para aprovacao']))
                            <a href="{{ route('gerar_orcamento', [
                                    'id' => $orcamento->id_orcamento,
                                    'cliente_orcamento_id' => $clienteSelecionado->id_co
                                ]) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                                style="background-color: #EA792D;">
                                Gerar Orçamento
                            </a>
                            @else
                            <a href="{{ route('detalhes_orcamento.create', ['orcamento_id' => $orcamento->id_orcamento]) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Cadastrar Detalhes
                            </a>
                            @endif

                            {{-- Editar --}}
                            <a href="{{ route('orcamento.edit', [
                                    'orcamento' => $orcamento->id_orcamento,
                                    'cliente_orcamento_id' => $clienteSelecionado->id_co
                                ]) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                                Editar
                            </a>
                            {{-- Excluir --}}
                            <form action="{{ route('orcamento.destroy', $orcamento->id_orcamento) }}" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir este orçamento?');"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-cancel-bg hover:bg-button-cancel-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-cancel-bg transition duration-150 ease-in-out">
                                    Excluir
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p class="text-gray-600 text-center py-8 hidden" id="noResultsOrcamentoMessage">Nenhum orçamento encontrado com
            esse termo de busca.</p>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const form = document.getElementById('filtroForm');
        const statusSelect = document.querySelector('select[name="status_query"]');
        const radios = document.querySelectorAll('input[name="filtro_vencimento"]');

        statusSelect?.addEventListener('change', function() {
            form.submit();
        });

        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                form.submit();
            });
        });

    });
</script>
@endsection