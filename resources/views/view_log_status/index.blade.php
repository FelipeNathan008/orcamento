@extends('layouts.app_financeiro')

@section('title', 'Lista de Logs de Status')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho da Seção --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Logs de Status
        </h1>
        <a href="{{ route('log_status.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
            style="background-color: #EA792D;">
            Novo Log
        </a>

    </div>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Formulário de Busca --}}
    <form id="filtroLogsForm" action="{{ route('log_status.index') }}" method="GET" class="w-full mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 relative">

            {{-- Campo Nome do Status --}}
            <div class="relative">
                <input type="text" name="search_status" id="searchStatusInput"
                    placeholder="Pesquisar por Nome do Status..."
                    value="{{ request('search_status') }}"
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus text-custom-dark-text">

                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>

            {{-- Campo ID Orçamento --}}
            <div class="relative">
                <input type="text" name="search_orcamento" id="searchOrcInput"
                    placeholder="Pesquisar por ID Orçamento..."
                    value="{{ request('search_orcamento') }}"
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus text-custom-dark-text">

                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>

            {{-- Campo ID Cliente --}}
            <div class="relative">
                <input type="text" name="search_cliente" id="searchClienteInput"
                    placeholder="Pesquisar por ID Cliente..."
                    value="{{ request('search_cliente') }}"
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus text-custom-dark-text">

                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4-816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>

            {{-- Botão "Limpar" --}}
            @if(request('search_status') || request('search_orcamento') || request('search_cliente'))
            <a href="{{ route('log_status.index') }}"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 hover:text-gray-700 font-medium hidden md:block">
                Limpar
            </a>
            @endif
        </div>
    </form>

    @if ($logs->isEmpty())
    <p class="text-gray-600 text-center py-8">Nenhum log cadastrado ainda.</p>
    @else

    {{-- Tabela --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Nome Status
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Situação
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        ID Orçamento
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        ID Cliente
                    </th>

                    <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Ações
                    </th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($logs as $log)
                <tr class="hover:bg-gray-50">

                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $log->log_nome_status }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $log->log_situacao == 1 ? 'Ativo' : 'Inativo' }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $log->log_id_orcamento }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $log->log_id_cliente }}
                    </td>

                    <td class="px-6 py-4 text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-2">

                            <a href="{{ route('log_status.show', $log->id_log_status) }}"
                                class="inline-flex items-center px-2 py-1 text-xs rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Ver
                            </a>

                            <a href="{{ route('log_status.edit', $log->id_log_status) }}"
                                class="inline-flex items-center px-2 py-1 text-xs rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover">
                                Editar
                            </a>

                            <form action="{{ route('log_status.destroy', $log->id_log_status) }}"
                                method="POST"
                                onsubmit="return confirm('Deseja excluir este log?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-2 py-1 text-xs rounded-md text-white bg-button-cancel-bg hover:bg-button-cancel-hover">
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