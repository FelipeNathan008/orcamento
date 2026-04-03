@extends('layouts.app')

@section('title', 'Fluxo de Caixa')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1 class="text-3xl sm:text-[32px] font-bold text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Fluxo de Caixa
        </h1>

        <div class="flex items-center gap-3">

            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400">
                HOME
            </a>

            <a href="{{ route('fluxo_caixa.create') }}"
                class="px-4 py-2 text-sm font-medium rounded-md text-white hover:brightness-90"
                style="background-color: #EA792D;">
                Novo Lançamento
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
                    Pesquisar
                </label>

                <div class="relative">

                    <input
                        type="text"
                        id="searchFluxoInput"
                        placeholder="Descrição ou tipo..."
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
                <button id="clearFiltersFluxo"
                    class="px-4 py-2 h-10 text-sm rounded-md bg-gray-200 hover:bg-gray-300">
                    Limpar Busca
                </button>
            </div>

        </div>

    </div>

    {{-- TABELA --}}
    @if ($fluxos->isEmpty())
    <p class="text-center text-gray-600">Nenhum lançamento encontrado.</p>
    @else

    <div class="w-full rounded-lg shadow-table-shadow-image mb-3 overflow-x-auto">
        <table class="min-w-full w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Data
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Tipo
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Movimentação
                    </th>

                    <th scope="col"
                        class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Valor
                    </th>

                    <th scope="col"
                        class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Descrição
                    </th>

                    <th scope="col"
                        class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Ações
                    </th>
                </tr>
            </thead>

            <tbody id="fluxoTableBody" class="bg-white divide-y divide-gray-200">

                @foreach ($fluxos as $fluxo)
                <tr class="hover:bg-gray-50"
                    data-desc="{{ $fluxo->flu_desc }}"
                    data-tipo="{{ $fluxo->tipo->tipo_flu_nome ?? '' }}">

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ \Carbon\Carbon::parse($fluxo->flu_data_despesa)->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $fluxo->tipo->tipo_flu_nome ?? 'N/A' }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $fluxo->movimentacao->mov_nome ?? 'N/A' }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        R$ {{ number_format($fluxo->flu_valor, 2, ',', '.') }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $fluxo->flu_desc }}
                    </td>

                    <td class="px-2 py-4 text-center text-sm">

                        <div class="flex justify-center gap-2">

                            <a href="{{ route('fluxo_caixa.show', $fluxo->id_fluxo) }}"
                                class="px-2 py-1 text-xs text-white rounded-md bg-blue-600 hover:bg-blue-700">
                                Ver
                            </a>

                            <a href="{{ route('fluxo_caixa.edit', $fluxo->id_fluxo) }}"
                                class="px-2 py-1 text-xs text-white rounded-md bg-button-edit-bg hover:bg-button-edit-hover">
                                Editar
                            </a>

                            <form action="{{ route('fluxo_caixa.destroy', $fluxo->id_fluxo) }}"
                                method="POST"
                                onsubmit="return confirm('Deseja excluir este lançamento?');">
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

        <p id="noResultsFluxo" class="text-center py-6 text-gray-500 hidden">
            Nenhum resultado encontrado.
        </p>

    </div>

    @endif

</div>

{{-- SCRIPT --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const searchInput = document.getElementById('searchFluxoInput');
        const clearBtn = document.getElementById('clearFiltersFluxo');

        const rows = Array.from(document.querySelectorAll('#fluxoTableBody tr'));
        const noResults = document.getElementById('noResultsFluxo');

        function filter() {
            const term = searchInput.value.toLowerCase();
            let found = false;

            rows.forEach(row => {
                const desc = row.dataset.desc.toLowerCase();
                const tipo = row.dataset.tipo.toLowerCase();

                if (desc.includes(term) || tipo.includes(term)) {
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