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

            <a href="{{ route('fluxo_nota_conta.index') }}"
                class="px-4 py-2 text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400">
                Voltar
            </a>

            <a href="{{ route('fluxo_caixa.create') }}"
                class="px-4 py-2 text-sm font-medium rounded-md text-white hover:brightness-90"
                style="background-color: #EA792D;">
                Novo Lançamento
            </a>

            <a id="btnExportarPdf"
                href="#"
                class="px-4 py-2 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 hidden">
                Exportar PDF
            </a>

        </div>

    </div>
    <div class="flex items-start gap-3">

        <div>
            <p class="text-sm text-yellow-700 mt-1">
                Para gerar o PDF, selecione uma data específica, pois o relatório é baseado no fluxo de caixa diário. </p>
        </div>

    </div>
    {{-- ALERT --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4">
        {{ session('success') }}
    </div>
    @endif
    {{-- ERROS --}}
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4">
        {{ session('error') }}
    </div>
    @endif

    {{-- FILTRO --}}
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">

            {{-- DATA --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Data Início
                </label>

                <input
                    type="date"
                    id="filterDateInicio"
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Data Fim
                </label>

                <input
                    type="date"
                    id="filterDateFim"
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500">
            </div>

            {{-- MOVIMENTAÇÃO --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Movimentação
                </label>

                <select id="filterMov"
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500">
                    <option value="">Todas</option>
                    <option value="entrada">Entrada</option>
                    <option value="saída">Saída</option>
                </select>
            </div>

            {{-- BOTÃO --}}
            <div class="flex md:justify-end">
                <button id="clearFiltersFluxo"
                    class="px-4 py-2 h-10 text-sm rounded-md bg-gray-200 hover:bg-gray-300">
                    Limpar Filtros
                </button>
            </div>

        </div>

    </div>


    <h2 class="text-3xl sm:text-[18px] font-bold text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
        Fluxo de Caixa do Dia
    </h2>

    <form method="GET" action="{{ route('fluxo_caixa.index') }}" class="mb-6">

        <select
            name="conta_bancaria_id"
            onchange="this.form.submit()"
            class="w-full border rounded px-3 py-2">

            <option value="">
                Selecione uma conta
            </option>

            @foreach($contas as $conta)

            <option
                value="{{ $conta->id_conta }}"
                {{ $contaSelecionada == $conta->id_conta ? 'selected' : '' }}>

                {{ $conta->conta_nome_banco }}

            </option>

            @endforeach

        </select>

    </form>


    <div class="mb-6 p-4 bg-gray-100 rounded-lg flex justify-around text-center">
        <div>
            <span class="font-bold text-lg text-yellow-600">Saldo Atual da Conta</span>
            <span class="block text-gray-900 text-lg">
                {{ $contaSelecionada ? 'R$ ' . number_format($saldoConta, 2, ',', '.') : 'Selecione uma conta' }}
            </span>
        </div>

        <div>
            <span class="font-bold text-lg text-green-600">Entrada Dia</span>
            <span class="block text-gray-900 text-lg">
                {{ $contaSelecionada ? 'R$ ' . number_format($entrada, 2, ',', '.') : 'Selecione uma conta' }}
            </span>
        </div>

        <div>
            <span class="font-bold text-lg text-red-600">Saída Dia</span>
            <span class="block text-gray-900 text-lg">
                {{ $contaSelecionada ? 'R$ ' . number_format($saida, 2, ',', '.') : 'Selecione uma conta' }}
            </span>
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
                        Conta Bancária
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Movimentação
                    </th>

                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Tipo Fiscal
                    </th>


                    <th scope="col"
                        class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Valor
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
                    data-data="{{ $fluxo->flu_data_despesa }}"
                    data-mov="{{ strtolower($fluxo->movimentacao->mov_nome ?? '') }}">

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ \Carbon\Carbon::parse($fluxo->flu_data_despesa)->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $fluxo->tipo->tipo_flu_nome ?? 'N/A' }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        @if($fluxo->conta)
                        {{ $fluxo->conta->conta_nome_banco }}
                        @else
                        N/A
                        @endif
                    </td>


                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $fluxo->movimentacao->mov_nome ?? 'N/A' }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $fluxo->flu_tipo_fiscal ?? 'N/A'}}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700">
                        R$ {{ number_format($fluxo->flu_valor, 2, ',', '.') }}
                    </td>

                    <td class="px-2 py-4 text-center text-sm">

                        <div class="flex justify-center gap-2">

                            <a href="{{ route('fluxo_caixa.show', $fluxo->id_fluxo) }}"
                                class="px-2 py-1 text-xs text-white rounded-md bg-blue-600 hover:bg-blue-700">
                                Ver
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

        const filterDateInicio = document.getElementById('filterDateInicio');
        const filterDateFim = document.getElementById('filterDateFim');
        const filterMov = document.getElementById('filterMov');
        const clearBtn = document.getElementById('clearFiltersFluxo');

        const btnPdf = document.getElementById('btnExportarPdf');

        const rows = Array.from(
            document.querySelectorAll('#fluxoTableBody tr')
        );

        const noResults = document.getElementById('noResultsFluxo');

        function filter() {

            const dataInicio = filterDateInicio.value;
            const dataFim = filterDateFim.value;
            const selectedMov = filterMov.value.toLowerCase();

            let found = false;
            if (
                dataInicio &&
                dataFim &&
                dataFim <= dataInicio
            ) {
                alert('A data final deve ser maior que a data inicial.');
                return;
            }
            rows.forEach(row => {

                const rowDate = row.dataset.data;
                const rowMov = row.dataset.mov;

                let matchDate = true;

                if (dataInicio && !dataFim) {

                    matchDate = rowDate === dataInicio;

                } else if (dataInicio && dataFim) {

                    matchDate =
                        rowDate >= dataInicio &&
                        rowDate <= dataFim;
                }

                const matchMov = !selectedMov ||
                    rowMov.includes(selectedMov);

                if (matchDate && matchMov) {

                    row.style.display = '';
                    found = true;

                } else {

                    row.style.display = 'none';
                }
            });

            noResults.classList.toggle('hidden', found);

            // PDF

            if (dataInicio) {

                btnPdf.classList.remove('hidden');

                let url =
                    `/fluxo-caixa/pdf?data_inicio=${dataInicio}`;

                if (dataFim) {

                    url += `&data_fim=${dataFim}`;
                }

                btnPdf.href = url;

            } else {

                btnPdf.classList.add('hidden');
            }
        }

        filterDateInicio.addEventListener('change', filter);
        filterDateFim.addEventListener('change', filter);
        filterMov.addEventListener('change', filter);

        clearBtn.addEventListener('click', () => {

            filterDateInicio.value = '';
            filterDateFim.value = '';
            filterMov.value = '';

            filter();

            btnPdf.classList.add('hidden');
        });


    });
    filterDateInicio.addEventListener('change', () => {

        if (filterDateInicio.value) {

            let dataMinima = new Date(filterDateInicio.value);

            dataMinima.setDate(dataMinima.getDate() + 1);

            filterDateFim.min = dataMinima.toISOString().split('T')[0];

            if (
                filterDateFim.value &&
                filterDateFim.value <= filterDateInicio.value
            ) {
                filterDateFim.value = '';
            }
        }

        filter();
    });
</script>
@endpush

@endsection