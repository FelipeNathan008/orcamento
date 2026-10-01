@extends('layouts.app')

@section('title', 'Fluxo de Caixa')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Fluxo de Caixa">
            <x-header-action href="{{ route('fluxo_caixa.create', ['return_url' => request()->fullUrl()]) }}">
                Novo Lançamento
            </x-header-action>
            <x-header-action id="btnExportarPdf" href="#" color="blue" class="hidden">
                Exportar PDF
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4">
            <div class="flex items-start gap-2 px-4 py-3 bg-yellow-50 border border-yellow-100 rounded-lg">
                <p class="text-sm text-yellow-700">
                    Para gerar o PDF, selecione uma data específica, pois o relatório é baseado no fluxo de caixa diário.
                </p>
            </div>
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('fluxo_caixa.index') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="data_inicio" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Data Início</label>
                            <input type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="data_fim" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Data Fim</label>
                            <input type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="movimentacao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Movimentação</label>
                            <select id="movimentacao" name="movimentacao"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todas</option>
                                <option value="entrada" {{ $movimentacao === 'entrada' ? 'selected' : '' }}>Entrada</option>
                                <option value="saída" {{ $movimentacao === 'saída' ? 'selected' : '' }}>Saída</option>
                            </select>
                        </div>

                        <div>
                            <label for="conta_bancaria_id" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Conta Bancária</label>
                            <select id="conta_bancaria_id" name="conta_bancaria_id"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todas</option>
                                @foreach($contas as $conta)
                                <option value="{{ $conta->id_conta }}" {{ request('conta_bancaria_id') == $conta->id_conta ? 'selected' : '' }}>
                                    {{ $conta->conta_nome_banco }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <x-primary-button class="w-full h-11">
                                <x-icons.search />
                                Buscar
                            </x-primary-button>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                        <x-header-action href="{{ route('fluxo_caixa.index') }}" color="gray">
                            <x-icons.reset />
                            Limpar filtros
                        </x-header-action>
                    </div>
                </x-filter-card>
            </form>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            @if($fluxos->isEmpty())
            @if(request('data_inicio') || request('data_fim') || request('movimentacao') || request('conta_bancaria_id'))
            <x-empty-state
                title="Nenhum lançamento encontrado"
                message="Não existem lançamentos correspondentes aos filtros informados."
                route="fluxo_caixa.index"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhum lançamento cadastrado"
                message="Ainda não existem lançamentos de fluxo de caixa cadastrados no sistema."
                route="fluxo_caixa.create"
                button-text="Novo lançamento" />
            @endif
            @else
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Lista de movimentações</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">
                        {{ $fluxos->total() }} lançamento(s)
                    </span>
                </div>
            </div>

            <div class="mb-5 p-4 bg-gray-50 border border-gray-200 rounded-xl">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-800">Resumo do Fluxo de Caixa</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            Selecione uma conta para visualizar o saldo e as movimentações do dia.
                        </p>
                    </div>

                    <form method="GET" action="{{ route('fluxo_caixa.index') }}" class="w-full sm:w-64">
                        @foreach(request()->except('conta_bancaria_id', 'page') as $key => $value)
                        @if(is_array($value))
                        @foreach($value as $item)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                        @endforeach
                        @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                        @endforeach

                        <select name="conta_bancaria_id" onchange="this.form.submit()"
                            class="w-full h-10 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            <option value="">Selecione uma conta</option>
                            @foreach($contas as $conta)
                            <option value="{{ $conta->id_conta }}" {{ $contaSelecionada == $conta->id_conta ? 'selected' : '' }}>
                                {{ $conta->conta_nome_banco }}
                            </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wide">Saldo Atual</span>
                        <span class="block mt-1 text-lg font-bold text-gray-800">
                            {{ $contaSelecionada ? 'R$ ' . number_format($saldoConta, 2, ',', '.') : 'Selecione uma conta' }}
                        </span>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wide">Entrada do Dia</span>
                        <span class="block mt-1 text-lg font-bold text-green-600">
                            {{ $contaSelecionada ? 'R$ ' . number_format($entrada, 2, ',', '.') : 'Selecione uma conta' }}
                        </span>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wide">Saída do Dia</span>
                        <span class="block mt-1 text-lg font-bold text-red-600">
                            {{ $contaSelecionada ? 'R$ ' . number_format($saida, 2, ',', '.') : 'Selecione uma conta' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Data</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Tipo</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Conta Bancária</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Movimentação</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Tipo Fiscal</th>
                                <th class="px-5 py-4 text-right text-[11px] font-bold text-white uppercase tracking-wider">Valor</th>
                                <th class="px-2 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($fluxos as $fluxo)
                            @php
                            $movNome = $fluxo->movimentacao->mov_nome ?? 'N/A';
                            $movTipo = str_contains(mb_strtolower($movNome), 'entrada') ? 'green' : 'red';
                            $valorTipo = $movTipo === 'green' ? 'text-green-600' : 'text-red-600';
                            @endphp

                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                            <x-icons.calendar class="w-4 h-4" />
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ \Carbon\Carbon::parse($fluxo->flu_data_despesa)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $fluxo->tipo->tipo_flu_nome ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $fluxo->conta->conta_nome_banco ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <x-badge :type="$movTipo" :text="$movNome" :dot="false" />
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">
                                        {{ $fluxo->flu_tipo_fiscal ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 text-right whitespace-nowrap">
                                    <span class="text-sm font-bold {{ $valorTipo }}">
                                        R$ {{ number_format($fluxo->flu_valor, 2, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-2 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :show-route="route('fluxo_caixa.show', $fluxo->id_fluxo)"
                                        show-text="Ver"
                                        :delete-action="route('fluxo_caixa.destroy', $fluxo->id_fluxo)"
                                        delete-id="formExcluirFluxo{{ $fluxo->id_fluxo }}"
                                        delete-modal="modalExcluirFluxo" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$fluxos" />
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirFluxo"
    titulo="Excluir fluxo"
    mensagem="Deseja realmente apagar este fluxo de caixa?"
    textoConfirmar="Excluir" />

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dataInicio = document.getElementById('data_inicio');
        const dataFim = document.getElementById('data_fim');
        const btnPdf = document.getElementById('btnExportarPdf');

        function atualizarPdf() {
            if (!dataInicio || !btnPdf) return;

            if (dataInicio.value) {
                btnPdf.classList.remove('hidden');
                let url = `{{ route('fluxo_caixa.pdf') }}?data_inicio=${dataInicio.value}`;
                if (dataFim?.value) url += `&data_fim=${dataFim.value}`;
                btnPdf.href = url;
            } else {
                btnPdf.classList.add('hidden');
            }
        }

        function atualizarDataFim() {
            if (!dataInicio || !dataFim) return;

            if (dataInicio.value) {
                const data = new Date(dataInicio.value + 'T00:00:00');
                data.setDate(data.getDate() + 1);
                dataFim.min = data.toISOString().split('T')[0];

                if (dataFim.value && dataFim.value <= dataInicio.value) {
                    dataFim.value = '';
                }
            } else {
                dataFim.removeAttribute('min');
            }

            atualizarPdf();
        }

        dataInicio?.addEventListener('change', atualizarDataFim);
        dataFim?.addEventListener('change', atualizarPdf);
        atualizarDataFim();
    });

    (function() {
        const scrollKey = 'fluxo_caixa.index.scroll';

        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        const savedScroll = sessionStorage.getItem(scrollKey);

        if (savedScroll !== null) {
            const position = parseInt(savedScroll, 10);

            const restaurarScroll = () => {
                window.scrollTo(0, position);
                sessionStorage.removeItem(scrollKey);
            };

            window.addEventListener('load', function() {
                setTimeout(restaurarScroll, 100);
            }, {
                once: true
            });

            setTimeout(restaurarScroll, 300);
        }

        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            const button = e.target.closest('button');

            const deveSalvar = link?.closest('tbody') ||
                link?.href?.includes('fluxo_caixa') ||
                link?.href?.includes('/create') ||
                link?.closest('.pagination') ||
                button?.closest('tbody');

            if (deveSalvar) {
                sessionStorage.setItem(scrollKey, window.scrollY);
            }
        });
    })();
</script>
@endpush
@endsection