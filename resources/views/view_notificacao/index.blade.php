{{-- resources/views/view_notificacao/index.blade.php --}}
@extends('layouts.app_financeiro')

@section('title', 'Notificações')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Notificações Cadastradas
        </h1>

        <div class="flex items-center gap-3">

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                HOME
            </a>
        </div>
    </div>

    {{-- Alerta sucesso --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Filtros --}}
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">

            {{-- ID Financeiro --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    ID Financeiro
                </label>
                <input
                    type="text"
                    id="searchFin"
                    placeholder="ID Financeiro..."
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>

            {{-- ID Orçamento --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    ID Orçamento
                </label>
                <input
                    type="text"
                    id="searchOrc"
                    placeholder="ID Orçamento..."
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>

            {{-- Cliente --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Cliente
                </label>
                <input
                    type="text"
                    id="searchCliente"
                    placeholder="Nome do cliente..."
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>

            {{-- Forma pagamento --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Forma Pagamento
                </label>
                <input
                    type="text"
                    id="searchForma"
                    placeholder="Forma pagamento..."
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>

            {{-- Limpar --}}
            <div class="flex items-end">
                <button
                    type="button"
                    id="clearFiltersNotificacao"
                    class="w-full inline-flex justify-center items-center px-4 py-2 h-10 border border-transparent text-sm font-medium rounded-md shadow-sm text-gray-700 bg-gray-200 hover:bg-gray-300">
                    Limpar
                </button>
            </div>

        </div>

    </div>

    {{-- Sem notificações --}}
    @if ($notificacoes->isEmpty())
    <p class="text-gray-600 text-center py-8" id="noNotificacoesMessage">
        Nenhuma notificação cadastrada ainda.
    </p>
    @else

    {{-- Tabela --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">

        <table class="min-w-full w-full divide-y divide-gray-200">

            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">ID Financeiro</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">ID Orçamento</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Cliente</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Forma Pagamento</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Tipo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Descrição</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Criado em</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase">Ações</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200" id="notificacaoTableBody">

                @foreach ($notificacoes as $notificacao)
                <tr>
                    {{-- ID Financeiro --}}
                    <td class="px-4 py-4 text-sm text-gray-900">
                        {{ $notificacao->cobranca->cobr_id_fin ?? 'N/A' }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-900">
                        {{ $notificacao->cobranca->cobr_id_orc ?? 'N/A' }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-900">
                        {{ $notificacao->cobranca->cobr_cliente ?? 'N/A' }}
                    </td>

                    {{-- Forma pagamento --}}
                    <td class="px-4 py-4 text-sm text-gray-900">
                        {{ $notificacao->cobranca->tipoPagamento->tipo_plano_fin ?? 'N/A' }}
                    </td>

                    {{-- Tipo da notificação --}}
                    <td class="px-4 py-4 text-sm text-gray-900 font-medium">
                        @switch($notificacao->not_tipo)
                        @case(1)
                        Aviso Bancário
                        @break
                        @case(2)
                        E-mail / Telefone
                        @break
                        @case(3)
                        Carta Registrada
                        @break
                        @case(4)
                        Protesto
                        @break
                        @default
                        Não informado
                        @endswitch

                    </td>
                    {{-- Descrição --}}
                    <td class="px-4 py-4 text-sm text-gray-900 truncate max-w-xs">
                        {{ $notificacao->not_descricao }}
                    </td>

                    {{-- Data --}}
                    <td class="px-4 py-4 text-sm text-gray-900">
                        {{ $notificacao->created_at->format('d/m/Y H:i') }}
                    </td>

                    {{-- Ações --}}
                    <td class="px-2 py-4 text-center text-sm font-medium">

                        <div class="flex items-center justify-center space-x-2">

                            <a href="{{ route('notificacao.edit', $notificacao->id_notificacao) }}"
                                class="px-2 py-1 text-xs font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover">
                                Editar
                            </a>

                            <form action="{{ route('notificacao.destroy', $notificacao->id_notificacao) }}"
                                method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir esta notificação?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="px-2 py-1 text-xs font-medium rounded-md text-white bg-button-cancel-bg hover:bg-button-cancel-hover">
                                    Excluir
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>
                @endforeach

            </tbody>

        </table>

        <p class="text-gray-600 text-center py-8 hidden" id="noResultsMessage">
            Nenhuma notificação encontrada com esse termo de busca.
        </p>

    </div>

    @endif

</div>

{{-- Script busca dinâmica --}}
@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const searchFin = document.getElementById('searchFin');
        const searchOrc = document.getElementById('searchOrc');
        const searchCliente = document.getElementById('searchCliente');
        const searchForma = document.getElementById('searchForma');

        const clearBtn = document.getElementById('clearFiltersNotificacao');
        const tableBody = document.getElementById('notificacaoTableBody');
        const rows = tableBody.querySelectorAll('tr');

        function filterTable() {

            const fin = searchFin.value.toLowerCase();
            const orc = searchOrc.value.toLowerCase();
            const cliente = searchCliente.value.toLowerCase();
            const forma = searchForma.value.toLowerCase();

            rows.forEach(row => {

                const colFin = row.cells[0].textContent.toLowerCase();
                const colOrc = row.cells[1].textContent.toLowerCase();
                const colCliente = row.cells[2].textContent.toLowerCase();
                const colForma = row.cells[3].textContent.toLowerCase();

                const matchFin = !fin || colFin.includes(fin);
                const matchOrc = !orc || colOrc.includes(orc);
                const matchCliente = !cliente || colCliente.includes(cliente);
                const matchForma = !forma || colForma.includes(forma);

                if (matchFin && matchOrc && matchCliente && matchForma) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }

            });

        }

        function clearFilters() {
            searchFin.value = '';
            searchOrc.value = '';
            searchCliente.value = '';
            searchForma.value = '';
            filterTable();
        }

        searchFin.addEventListener('input', filterTable);
        searchOrc.addEventListener('input', filterTable);
        searchCliente.addEventListener('input', filterTable);
        searchForma.addEventListener('input', filterTable);

        clearBtn.addEventListener('click', clearFilters);

    });
</script>

@endpush
@endsection