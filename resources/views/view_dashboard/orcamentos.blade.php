@extends('layouts.app')

@section('title', 'Lista de Orçamentos')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Orçamentos Cadastrados" :back-url="route('dashboard')" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('dashboard.orcamentos') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                        <div>
                            <label for="id_orcamento" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">ID Orçamento</label>
                            <input type="number" id="id_orcamento" name="id_orcamento" value="{{ request('id_orcamento') }}" placeholder="Ex.: 80"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="orc_cod_interno" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Código Interno</label>
                            <input type="text" id="orc_cod_interno" name="orc_cod_interno" value="{{ request('orc_cod_interno') }}" placeholder="Código interno..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="orc_cod_fabrica" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Código da Fábrica</label>
                            <input type="text" id="orc_cod_fabrica" name="orc_cod_fabrica" value="{{ request('orc_cod_fabrica') }}" placeholder="Código fábrica..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="ano" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Ano</label>
                            <input type="number" id="ano" name="ano" value="{{ request('ano') }}" placeholder="Ex.: 2026" min="1900" max="2100"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="mes" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Mês</label>
                            <select id="mes" name="mes"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todos</option>
                                <option value="1" {{ request('mes') == '1' ? 'selected' : '' }}>Janeiro</option>
                                <option value="2" {{ request('mes') == '2' ? 'selected' : '' }}>Fevereiro</option>
                                <option value="3" {{ request('mes') == '3' ? 'selected' : '' }}>Março</option>
                                <option value="4" {{ request('mes') == '4' ? 'selected' : '' }}>Abril</option>
                                <option value="5" {{ request('mes') == '5' ? 'selected' : '' }}>Maio</option>
                                <option value="6" {{ request('mes') == '6' ? 'selected' : '' }}>Junho</option>
                                <option value="7" {{ request('mes') == '7' ? 'selected' : '' }}>Julho</option>
                                <option value="8" {{ request('mes') == '8' ? 'selected' : '' }}>Agosto</option>
                                <option value="9" {{ request('mes') == '9' ? 'selected' : '' }}>Setembro</option>
                                <option value="10" {{ request('mes') == '10' ? 'selected' : '' }}>Outubro</option>
                                <option value="11" {{ request('mes') == '11' ? 'selected' : '' }}>Novembro</option>
                                <option value="12" {{ request('mes') == '12' ? 'selected' : '' }}>Dezembro</option>
                            </select>
                        </div>

                        <div>
                            <label for="status_query" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Status</label>
                            <select id="status_query" name="status_query"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todos</option>
                                <option value="pendente" {{ request('status_query') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="para aprovacao" {{ request('status_query') === 'para aprovacao' ? 'selected' : '' }}>Para Aprovação</option>
                                <option value="aprovado" {{ request('status_query') === 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                                <option value="finalizado" {{ request('status_query') === 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                                <option value="rejeitado" {{ request('status_query') === 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                            </select>
                        </div>

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
                            <label for="filtro_vencimento" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Vencimento</label>
                            <select id="filtro_vencimento" name="filtro_vencimento"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="todos" {{ request('filtro_vencimento', 'todos') === 'todos' ? 'selected' : '' }}>Todos</option>
                                <option value="ativos" {{ request('filtro_vencimento') === 'ativos' ? 'selected' : '' }}>Ativos</option>
                                <option value="vencidos" {{ request('filtro_vencimento') === 'vencidos' ? 'selected' : '' }}>Vencidos</option>
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
                        <a href="{{ route('dashboard.orcamentos') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 hover:text-gray-800 transition">
                            <x-icons.reset />
                            Limpar filtros
                        </a>
                    </div>
                </x-filter-card>
            </form>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Lista de orçamentos</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">{{ $orcamentos->total() }} orçamento(s)</span>
                </div>
            </div>

            @if ($orcamentos->isEmpty())
            @if (request('id_orcamento') || request('orc_cod_fabrica') || request('orc_cod_interno') || request('ano') || request('data_inicio') || request('data_fim') || request('mes') || request('status_query') || request('filtro_vencimento'))
            <x-empty-state
                title="Nenhum orçamento encontrado"
                message="Não existem orçamentos correspondentes aos filtros informados."
                route="dashboard.orcamentos"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhum orçamento cadastrado"
                message="Ainda não existem orçamentos cadastrados."
                route="dashboard.orcamentos"
                button-text="Atualizar listagem" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Orçamento</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Cliente</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Códigos</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Período</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Status</th>
                                <th class="px-5 py-4 text-right text-[11px] font-bold text-white uppercase tracking-wider">Valor Total</th>
                                <th class="px-2 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($orcamentos as $orcamento)
                            @php
                            $totalBrutoCalculado = 0;
                            foreach ($orcamento->detalhesOrcamento as $detalhe) {
                            $quantidade = (int) ($detalhe->det_quantidade ?? 0);
                            $totalBrutoCalculado += $quantidade * (float) ($detalhe->det_valor_unit ?? 0);
                            foreach ($detalhe->customizacoes as $customizacao) {
                            $totalBrutoCalculado += $quantidade * (float) ($customizacao->cust_valor ?? 0);
                            }
                            }

                            $valorDescontoCalculado = match ($orcamento->orc_desconto_tipo) {
                            'percentual' => $totalBrutoCalculado * ((float) ($orcamento->orc_desconto_valor ?? 0) / 100),
                            'valor' => (float) ($orcamento->orc_desconto_valor ?? 0),
                            default => 0,
                            };

                            $valorDescontoCalculado = min($valorDescontoCalculado, $totalBrutoCalculado);
                            $totalAtual = $totalBrutoCalculado - $valorDescontoCalculado;

                            $statusTipo = [
                            'pendente' => 'yellow',
                            'para aprovacao' => 'blue',
                            'aprovado' => 'green',
                            'rejeitado' => 'red',
                            'finalizado' => 'emerald',
                            ][$orcamento->orc_status] ?? 'gray';

                            $clienteIdCriptografado = CryptHelper::encrypt($orcamento->cliente_orcamento_id_co);
                            @endphp

                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                            <x-icons.document />
                                        </div>
                                        <span class="text-sm font-bold text-gray-800">#{{ $orcamento->id_orcamento }}</span>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'N/D' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="w-8 text-[10px] font-bold text-gray-400">INT.</span>
                                            <span class="font-semibold text-gray-700">{{ $orcamento->orc_cod_interno ?: 'N/D' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="w-8 text-[10px] font-bold text-gray-400">FAB.</span>
                                            <span class="font-semibold text-gray-700">{{ $orcamento->orc_cod_fabrica ?: 'N/D' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-2 text-xs text-gray-700">
                                            <x-icons.calendar class="w-4 h-4 text-gray-400" />
                                            <span>
                                                {{ $orcamento->orc_data_inicio?->format('d/m/Y') ?? 'N/D' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-gray-700">
                                            <x-icons.calendar class="w-4 h-4 text-gray-400" />
                                            <span>
                                                {{ $orcamento->orc_data_fim?->format('d/m/Y') ?? 'N/D' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <x-badge :type="$statusTipo" :text="ucfirst($orcamento->orc_status)" />
                                </td>

                                <td class="px-5 py-5 text-right whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-800">
                                        R$ {{ number_format($totalAtual, 2, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-2 py-5 text-center whitespace-nowrap">
                                    <a href="{{ route('orcamento.index', ['id' => $clienteIdCriptografado]) }}"
                                        class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-white shadow-sm hover:shadow-md hover:brightness-95 transition"
                                        style="background-color:#EA792D;">
                                        Detalhes
                                        <x-icons.arrow-right />
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$orcamentos" />
            </div>
            @endif
        </div>
    </div>
</div>
@endsection