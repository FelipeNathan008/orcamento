@extends('layouts.app')

@section('title', 'Orçamentos Fracionados')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Orçamentos Fracionados" :back-url="route('dashboard')" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('dashboard.orcamentos.fracionados') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                        <div>
                            <label for="orcamento_id_orcamento" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                ID Orç. Principal
                            </label>
                            <input type="number" id="orcamento_id_orcamento" name="orcamento_id_orcamento"
                                value="{{ request('orcamento_id_orcamento') }}" placeholder="Ex.: 80"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="id_orcamento_fracionado" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                ID Orç. Fracionado
                            </label>
                            <input type="number" id="id_orcamento_fracionado" name="id_orcamento_fracionado"
                                value="{{ request('id_orcamento_fracionado') }}" placeholder="Ex.: 80"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="orc_cod_interno" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Código Interno
                            </label>
                            <input type="text" id="orc_cod_interno" name="orc_cod_interno"
                                value="{{ request('orc_cod_interno') }}" placeholder="Código interno..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="orc_cod_fabrica" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Código da Fábrica
                            </label>
                            <input type="text" id="orc_cod_fabrica" name="orc_cod_fabrica"
                                value="{{ request('orc_cod_fabrica') }}" placeholder="Código fábrica..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="status_query" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Status
                            </label>
                            <select id="status_query" name="status_query"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todos</option>
                                <option value="pendente" {{ request('status_query') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="pedido fabrica" {{ request('status_query') == 'pedido fabrica' ? 'selected' : '' }}>Pedido Fábrica</option>
                                <option value="transportadora" {{ request('status_query') == 'transportadora' ? 'selected' : '' }}>Transportadora</option>
                                <option value="entregue" {{ request('status_query') == 'entregue' ? 'selected' : '' }}>Entregue</option>
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
                        <a href="{{ route('dashboard.orcamentos.fracionados') }}"
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
                <h2 class="text-lg font-bold text-gray-800">Lista de orçamentos fracionados</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">{{ $orcamentos->total() }} orçamento(s)</span>
                </div>
            </div>

            @if ($orcamentos->isEmpty())
            @if (request('orcamento_id_orcamento') || request('id_orcamento_fracionado') || request('orc_cod_fabrica') || request('orc_cod_interno') || request('status_query'))
            <x-empty-state
                title="Nenhum orçamento fracionado encontrado"
                message="Não existem orçamentos fracionados correspondentes aos filtros informados."
                route="dashboard.orcamentos.fracionados"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhum orçamento fracionado cadastrado"
                message="Ainda não existem orçamentos fracionados cadastrados."
                route="dashboard.orcamentos.fracionados"
                button-text="Atualizar listagem" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Identificação</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Cliente</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Códigos</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Status</th>
                                <th class="px-5 py-4 text-right text-[11px] font-bold text-white uppercase tracking-wider">Valor Total</th>
                                <th class="px-2 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($orcamentos as $orcamento)
                            @php
                            $statusTipo = match ($orcamento->orc_status) {
                            'pendente' => 'yellow',
                            'pedido fabrica' => 'purple',
                            'transportadora' => 'blue',
                            'entregue' => 'green',
                            default => 'gray',
                            };

                            $statusNome = match ($orcamento->orc_status) {
                            'pendente' => 'Pendente',
                            'pedido fabrica' => 'Pedido fábrica',
                            'transportadora' => 'Transportadora',
                            'entregue' => 'Entregue',
                            default => ucfirst($orcamento->orc_status),
                            };
                            @endphp

                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                            <x-icons.document />
                                        </div>
                                        <div class="space-y-1.5">
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="w-10 text-[10px] font-bold text-gray-400">FRAC.</span>
                                                <span class="font-bold text-gray-800">#{{ $orcamento->id_orcamento_fracionado }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="w-10 text-[10px] font-bold text-gray-400">ORÇ.</span>
                                                <span class="font-semibold text-gray-700">#{{ $orcamento->orcamento_id_orcamento }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs">
                                                <span class="w-10 text-[10px] font-bold text-gray-400">FRAÇÃO</span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-[11px] font-semibold text-gray-700">
                                                    #{{ $orcamento->orc_fracao }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'N/D' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="w-8 text-[10px] font-bold text-gray-400">INT.</span>
                                            <span class="font-semibold text-gray-700">
                                                {{ $orcamento->orc_cod_interno ?: 'N/D' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="w-8 text-[10px] font-bold text-gray-400">FAB.</span>
                                            <span class="font-semibold text-gray-700">
                                                {{ $orcamento->orc_cod_fabrica ?: 'N/D' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <x-badge :type="$statusTipo" :text="$statusNome" />
                                </td>

                                <td class="px-5 py-5 text-right whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-800">
                                        R$ {{ number_format((float) ($orcamento->total_bruto ?? 0), 2, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-2 py-5 text-center whitespace-nowrap">
                                    <a href="{{ route('orcamento.fracionado.index', $orcamento->orcamento_id_orcamento) }}"
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