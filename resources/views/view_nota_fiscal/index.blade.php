@extends('layouts.app')

@section('title', 'Notas Fiscais')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1 class="text-3xl sm:text-[32px] font-bold text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Notas Fiscais
        </h1>

        <div class="flex items-center gap-3">

            <a href="{{ route('fluxo_nota_conta.index') }}"
                class="px-4 py-2 text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400">
                Voltar
            </a>

            <a href="{{ route('nota_fiscal.create') }}"
                class="px-4 py-2 text-sm font-medium rounded-md text-white hover:brightness-90"
                style="background-color: #EA792D;">
                Nova Nota
            </a>

        </div>

    </div>

    {{-- ALERTAS --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4">
        {{ session('error') }}
    </div>
    @endif

    {{-- FILTROS --}}
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">

            {{-- DATA --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Filtrar por Data
                </label>

                <input
                    type="date"
                    id="filterDate"
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500">
            </div>

            {{-- TIPO --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tipo
                </label>

                <select id="filterTipo"
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500">
                    <option value="">Todos</option>
                    @foreach($tipos as $tipo)
                    <option value="{{ strtolower($tipo->tipo_flu_nome) }}">
                        {{ $tipo->tipo_flu_nome }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- BOTÃO --}}
            <div class="flex md:justify-end">
                <button id="clearFilters"
                    class="px-4 py-2 h-10 text-sm rounded-md bg-gray-200 hover:bg-gray-300">
                    Limpar Filtros
                </button>
            </div>

        </div>

    </div>

    {{-- TABELA --}}
    @if ($notas->isEmpty())
    <p class="text-center text-gray-600">Nenhuma nota fiscal encontrada.</p>
    @else

    <div class="w-full rounded-lg shadow-table-shadow-image mb-3 overflow-x-auto">
        <table class="min-w-full w-full divide-y divide-gray-200">

            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Data</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Orçamento</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Número</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Tipo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Movimentação</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">Valor</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">Ações</th>
                </tr>
            </thead>

            <tbody id="notaTableBody" class="bg-white divide-y divide-gray-200">

                @foreach ($notas as $nota)
                <tr class="hover:bg-gray-50"
                    data-data="{{ $nota->nota_data }}"
                    data-tipo="{{ strtolower($nota->tipo->tipo_flu_nome ?? '') }}">

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ \Carbon\Carbon::parse($nota->nota_data)->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $nota->orcamento_id_orcamento }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $nota->nota_numero }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $nota->tipo->tipo_flu_nome ?? 'N/A' }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $nota->movimentacao->mov_nome ?? 'N/A' }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700 text-center">
                        R$ {{ number_format($nota->nota_valor, 2, ',', '.') }}
                    </td>

                    <td class="px-2 py-4 text-center text-sm">

                        <div class="flex justify-center gap-2">

                            <a href="{{ route('nota_fiscal.show', $nota->id_nota_fiscal) }}"
                                class="px-2 py-1 text-xs text-white rounded-md bg-blue-600 hover:bg-blue-700">
                                Ver
                            </a>

                            <a href="{{ route('nota_fiscal.edit', $nota) }}"
                                class="px-2 py-1 text-xs text-white rounded-md bg-button-edit-bg hover:bg-button-edit-hover">
                                Editar
                            </a>

                            <form action="{{ route('nota_fiscal.destroy', $nota->id_nota_fiscal) }}"
                                method="POST"
                                onsubmit="return confirm('Deseja excluir?');">
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

        <p id="noResults" class="text-center py-6 text-gray-500 hidden">
            Nenhum resultado encontrado.
        </p>

    </div>

    @endif

</div>

{{-- SCRIPT --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const filterDate = document.getElementById('filterDate');
        const filterTipo = document.getElementById('filterTipo');
        const clearBtn = document.getElementById('clearFilters');

        const rows = Array.from(document.querySelectorAll('#notaTableBody tr'));
        const noResults = document.getElementById('noResults');

        function filter() {

            const date = filterDate.value;
            const tipo = filterTipo.value.toLowerCase();

            let found = false;

            rows.forEach(row => {

                const rowDate = row.dataset.data;
                const rowTipo = row.dataset.tipo;

                let matchDate = !date || rowDate === date;
                let matchTipo = !tipo || rowTipo.includes(tipo);

                if (matchDate && matchTipo) {
                    row.style.display = '';
                    found = true;
                } else {
                    row.style.display = 'none';
                }
            });

            noResults.classList.toggle('hidden', found);
        }

        filterDate.addEventListener('change', filter);
        filterTipo.addEventListener('change', filter);

        clearBtn.addEventListener('click', () => {
            filterDate.value = '';
            filterTipo.value = '';
            filter();
        });

    });
</script>
@endpush

@endsection