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
        <a href="{{ route('notificacao.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
            style="background-color: #EA792D;">
            Nova Notificação
        </a>
    </div>

    {{-- Alerta de sucesso --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Campo de busca --}}
    <div class="relative w-full mb-6">
        <input type="text"
            id="searchNotificacaoInput"
            value="{{ request('cobranca_id') }}"
            placeholder="Pesquisar Notificação..."
            class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none
                   hover:border-custom-border-hover focus:border-custom-border-focus text-custom-dark-text">
        <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd" />
        </svg>
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
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Tipo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Descrição</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Criado em</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Atualizado em</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase">Ações</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200" id="notificacaoTableBody">
                @foreach ($notificacoes as $notificacao)
                <tr>
                    <td class="px-4 py-4 text-sm text-gray-900">
                        {{ $notificacao->tipo_nome }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-900 truncate max-w-xs">
                        {{ $notificacao->not_descricao }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-900">
                        {{ $notificacao->created_at->format('d/m/Y H:i') }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-900">
                        {{ $notificacao->updated_at->format('d/m/Y H:i') }}
                    </td>

                    <td class="px-2 py-4 text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('notificacao.edit', $notificacao->id_notificacao) }}"
                                class="px-2 py-1 text-xs font-medium rounded-md text-white bg-button-edit-bg">
                                Editar
                            </a>

                            <form action="{{ route('notificacao.destroy', $notificacao->id_notificacao) }}"
                                method="POST"
                                onsubmit="return confirm('Tem certeza?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-2 py-1 text-xs font-medium rounded-md text-white bg-button-cancel-bg">
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

{{-- Script de busca dinâmica --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchNotificacaoInput');
        const tableBody = document.getElementById('notificacaoTableBody');
        const noResultsMessage = document.getElementById('noResultsMessage');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = tableBody.querySelectorAll('tr');
            let found = false;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    found = true;
                } else {
                    row.style.display = 'none';
                }
            });

            searchTerm === '' || found ?
                noResultsMessage.classList.add('hidden') :
                noResultsMessage.classList.remove('hidden');
        });
    });
    window.addEventListener('DOMContentLoaded', () => {
        if (searchInput.value) {
            searchInput.dispatchEvent(new Event('input'));
        }
    });
</script>
@endpush
@endsection