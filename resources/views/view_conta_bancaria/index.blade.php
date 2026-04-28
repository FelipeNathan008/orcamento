@extends('layouts.app')

@section('title', 'Contas Bancárias')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1 class="text-3xl sm:text-[32px] font-bold text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Contas Bancárias
        </h1>

        <div class="flex items-center gap-3">

            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400">
                HOME
            </a>

            <a href="{{ route('conta_bancaria.create') }}"
                class="px-4 py-2 text-sm font-medium rounded-md text-white hover:brightness-90"
                style="background-color: #EA792D;">
                Nova Conta
            </a>

        </div>

    </div>

    {{-- ALERT --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4">
        {{ session('success') }}
    </div>
    @endif

    {{-- FILTRO --}}
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pesquisar Conta
                </label>

                <div class="relative">

                    <input
                        type="text"
                        id="searchContaInput"
                        placeholder="Banco ou descrição..."
                        class="w-full h-10 pl-10 pr-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500">

                    <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 text-gray-500"
                        fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>

                </div>
            </div>

            <div class="flex md:justify-end">
                <button id="clearFiltersConta"
                    class="px-4 py-2 h-10 text-sm rounded-md bg-gray-200 hover:bg-gray-300">
                    Limpar Busca
                </button>
            </div>

        </div>

    </div>

    {{-- TABELA --}}
    @if ($contas->isEmpty())
    <p class="text-center text-gray-600">Nenhuma conta cadastrada.</p>
    @else

    <div class="w-full rounded-lg shadow-table-shadow-image mb-3 overflow-x-auto">
        <table class="min-w-full w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>

                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Banco</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Código</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Conta</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Dígito</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Descrição</th>

                    <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase">Ações</th>
                </tr>
            </thead>

            <tbody id="contaTableBody" class="bg-white divide-y divide-gray-200">

                @foreach ($contas as $conta)
                <tr class="hover:bg-gray-50"
                    data-nome="{{ $conta->conta_nome_banco }}"
                    data-desc="{{ $conta->conta_desc }}">

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $conta->conta_nome_banco }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $conta->conta_cod_banco }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $conta->numero_conta_corrente }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $conta->numero_digito_corrente }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $conta->conta_desc }}
                    </td>

                    <td class="px-2 py-4 text-center text-sm">

                        <div class="flex justify-center gap-2">

                            <a href="{{ route('conta_bancaria.show', $conta->id_conta) }}"
                                class="px-2 py-1 text-xs text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                Ver
                            </a>

                            <a href="{{ route('conta_bancaria.edit', $conta->id_conta) }}"
                                class="px-2 py-1 text-xs text-white rounded-md bg-button-edit-bg hover:bg-button-edit-hover">
                                Editar
                            </a>

                            <form action="{{ route('conta_bancaria.destroy', $conta->id_conta) }}"
                                method="POST"
                                onsubmit="return confirm('Deseja excluir esta conta?');">
                                @csrf
                                @method('DELETE')

                                <button
                                    class="px-2 py-1 text-xs text-white rounded-md bg-button-cancel-bg hover:bg-button-cancel-hover">
                                    Excluir
                                </button>
                            </form>

                        </div>

                    </td>

                </tr>
                @endforeach

            </tbody>

        </table>

        <p id="noResultsConta" class="text-center py-6 text-gray-500 hidden">
            Nenhum resultado encontrado.
        </p>

    </div>

    @endif

</div>

{{-- SCRIPT --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const searchInput = document.getElementById('searchContaInput');
    const clearBtn = document.getElementById('clearFiltersConta');

    const rows = Array.from(document.querySelectorAll('#contaTableBody tr'));
    const noResults = document.getElementById('noResultsConta');

    function filter() {
        const term = searchInput.value.toLowerCase();
        let found = false;

        rows.forEach(row => {
            const nome = row.dataset.nome.toLowerCase();
            const desc = (row.dataset.desc || '').toLowerCase();

            if (nome.includes(term) || desc.includes(term)) {
                row.style.display = '';
                found = true;
            } else {
                row.style.display = 'none';
            }
        });

        noResults.classList.toggle('hidden', found);
    }

    searchInput.addEventListener('input', filter);

    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        filter();
    });

});
</script>
@endpush

@endsection