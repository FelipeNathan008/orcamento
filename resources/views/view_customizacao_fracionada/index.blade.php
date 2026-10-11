@extends('layouts.app_financeiro')

@section('title', 'Customizações do Fracionado')

@php
use App\Helpers\CryptHelper;

$urlDetalhes = route(
'detalhes_orcamento_fracionado.index',
CryptHelper::encrypt($orcamentoFracionado->id_orcamento_fracionado)
);
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Customizações do Produto" :back-url="$urlDetalhes" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <x-info-card
                title="Informações do Item"
                :name="'Produto: ' . ($detalhe->det_nome).' | Código: '. ($detalhe->det_cod)"
                type="Produto"
                :fields="[
                    [
                        'label' => 'ID Orçamento fracionado',
                        'value' => $orcamentoFracionado->id_orcamento_fracionado,
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cód. Interno',
                        'value' => $orcamentoFracionado->orc_cod_interno ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cód. Fábrica',
                        'value' => $orcamentoFracionado->orc_cod_fabrica ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cliente',
                        'value' => $cliente->clie_orc_nome ?? 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Fração',
                        'value' => '#' . $orcamentoFracionado->orc_fracao,
                    ],
                    [
                        'label' => 'Código',
                        'value' =>$detalhe->det_cod,
                    ],
                    [
                        'label' => 'Categoria',
                        'value' => $detalhe->det_categoria ?: 'Não informado',
                    ],
                    [
                        'label' => 'Cor / Tamanho',
                        'value' => ($detalhe->det_cor ?: 'N/A') . ' - ' . ($detalhe->det_tamanho ?: 'N/A'),
                    ],
                    [
                        'label' => 'Características',
                        'value' => $detalhe->det_caract ?: 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Quantidade',
                        'value' => $detalhe->det_quantidade ?? 'Não informado',
                    ],
                ]" />

            <x-summary-cards :cards="[
                [
                    'title' => 'Total do Item',
                    'value' => 'R$ ' . number_format($totalItem, 2, ',', '.'),
                ],
                [
                    'title' => 'Total das Customizações',
                    'value' => 'R$ ' . number_format($totalCustomizacoes, 2, ',', '.'),
                ],
                [
                    'title' => 'Total Geral',
                    'value' => 'R$ ' . number_format($totalGeral, 2, ',', '.'),
                    'type' => 'primary',
                    'size' => 'xl',
                ],
            ]" />

            <div class="mt-4 text-sm text-yellow-600">
                Os valores abaixo são conforme o item selecionado no orçamento fracionado.
            </div>

            <form method="GET"
                action="{{ route('customizacao_fracionado.index', CryptHelper::encrypt($detalhe->id_det_fracionado)) }}"
                class="mt-6">

                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="tipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Tipo
                            </label>
                            <select id="tipo" name="tipo" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todos</option>
                                @foreach($tipos as $tipo)
                                <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>
                                    {{ $tipo }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="local" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Local
                            </label>
                            <select id="local" name="local" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todos</option>
                                @foreach($locais as $local)
                                <option value="{{ $local }}" {{ request('local') == $local ? 'selected' : '' }}>
                                    {{ $local }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="posicao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Posição
                            </label>
                            <select id="posicao" name="posicao" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todas</option>
                                @foreach($posicoes as $posicao)
                                <option value="{{ $posicao }}" {{ request('posicao') == $posicao ? 'selected' : '' }}>
                                    {{ $posicao }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="formatacao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Formatação
                            </label>
                            <select id="formatacao" name="formatacao" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todas</option>
                                @foreach($formatacoes as $formatacao)
                                <option value="{{ $formatacao }}" {{ request('formatacao') == $formatacao ? 'selected' : '' }}>
                                    {{ $formatacao }}
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
                        <a href="{{ route('customizacao_fracionado.index', CryptHelper::encrypt($detalhe->id_det_fracionado)) }}"
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
                <h2 class="text-lg font-bold text-gray-800">Customizações do produto</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">
                        {{ $customizacoes->total() }} customização(ões)
                    </span>
                </div>
            </div>

            @if($customizacoes->isEmpty())
            @if(request('tipo') || request('local') || request('posicao') || request('formatacao'))
            <x-empty-state
                title="Nenhuma customização encontrada"
                message="Não existem customizações correspondentes aos filtros informados."
                route="customizacao_fracionado.index"
                :route-params="CryptHelper::encrypt($detalhe->id_det_fracionado)"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhuma customização cadastrada"
                message="Ainda não existem customizações cadastradas para este item."
                route="detalhes_orcamento_fracionado.index"
                :route-params="CryptHelper::encrypt($orcamentoFracionado->id_orcamento_fracionado)"
                button-text="Voltar" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Tipo</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Local</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Posição</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Formatação</th>
                                <th class="px-5 py-4 text-right text-[11px] font-bold text-white uppercase tracking-wider">Valor</th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Imagem</th>
                                <th class="px-2 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($customizacoes as $customizacao)
                            <tr class="group transition hover:bg-orange-50/40">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-800">
                                        {{ $customizacao->cust_tipo ?: 'N/D' }}
                                    </span>
                                </td>
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">
                                        {{ $customizacao->cust_local ?: 'N/D' }}
                                    </span>
                                </td>
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">
                                        {{ $customizacao->cust_posicao ?: 'N/D' }}
                                    </span>
                                </td>
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">
                                        {{ $customizacao->cust_formatacao ?: 'N/D' }}
                                    </span>
                                </td>
                                <td class="px-5 py-5 text-right whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">
                                        R$ {{ number_format($customizacao->cust_valor, 2, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-5 py-5 text-center whitespace-nowrap">
                                    @if($customizacao->cust_imagem)
                                    <img src="{{ asset('images_customizacoes/' . $customizacao->cust_imagem) }}"
                                        class="w-16 h-16 object-cover rounded-lg shadow-sm mx-auto"
                                        alt="Imagem da customização">
                                    @else
                                    <span class="text-sm text-gray-500">Sem imagem</span>
                                    @endif
                                </td>
                                <td class="px-2 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :show-route="route('customizacao_fracionado.show', CryptHelper::encrypt($customizacao->id_customizacao_fracionada))"
                                        show-text="Ver" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$customizacoes" />
            </div>
            @endif
        </div>
    </div>
</div>
@endsection