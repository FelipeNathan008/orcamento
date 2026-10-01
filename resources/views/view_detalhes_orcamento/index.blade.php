@extends('layouts.app')

@section('title', 'Lista de Detalhes de Orçamentos')

@php
use App\Helpers\CryptHelper;

$orcamentoBloqueado = in_array(
strtolower(trim($orcamento->orc_status)),
['aprovado', 'finalizado', 'rejeitado']
);
@endphp

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Detalhes de Orçamento" :back-url="$urlOrcamento">
            @if(!$orcamentoBloqueado)
            <x-header-action href="{{ route('detalhes_orcamento.create',
            ['id' => CryptHelper::encrypt($orcamento->id_orcamento), 'return_url' => request()->fullUrl(),]) }}"
                data-detalhes-create>
                Novo Detalhe
            </x-header-action>
            @endif
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <x-info-card
                title="Informações do Orçamento"
                :name="'Orçamento #' . $orcamento->id_orcamento"
                type="Orçamento"
                :fields="[
                    [
                        'label' => 'Cód. Interno',
                        'value' => $orcamento->orc_cod_interno ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cód. Fábrica',
                        'value' => $orcamento->orc_cod_fabrica ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cliente',
                        'value' => $orcamento->clienteOrcamento->clie_orc_nome ?? 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Data de início',
                        'value' => $orcamento->orc_data_inicio ? $orcamento->orc_data_inicio->format('d/m/Y') : 'Não informado',
                    ],
                    [
                        'label' => 'Status',
                        'value' => ucfirst($orcamento->orc_status),
                    ],
                ]" />

            @php
            $totalDetalhes = 0;
            $totalCustomizacoes = 0;

            foreach ($orcamento->detalhesOrcamento as $detalhe) {
            $quantidade = (int) ($detalhe->det_quantidade ?? 0);
            $valorUnitario = (float) ($detalhe->det_valor_unit ?? 0);
            $totalDetalhes += $quantidade * $valorUnitario;

            foreach ($detalhe->customizacoes as $customizacao) {
            $totalCustomizacoes += $quantidade * (float) ($customizacao->cust_valor ?? 0);
            }
            }

            $totalGeral = $totalDetalhes + $totalCustomizacoes;
            @endphp

            <x-summary-cards :cards="[
                [
                    'title' => 'Total dos Itens',
                    'value' => 'R$ ' . number_format($totalDetalhes, 2, ',', '.'),
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
            <form method="GET" action="{{ route('detalhes_orcamento.index', CryptHelper::encrypt($orcamento->id_orcamento)) }}" class="mt-6">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="produto" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Produto</label>
                            <input type="text" id="produto" name="produto" value="{{ request('produto') }}" placeholder="Digite o nome..." class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>
                        <div>
                            <label for="categoria" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Categoria</label>
                            <input type="text" id="categoria" name="categoria" value="{{ request('categoria') }}" placeholder="Digite a categoria..." class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>
                        <div>
                            <label for="cod_ref" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Código Ref.</label>
                            <input type="text" id="cod_ref" name="cod_ref" value="{{ request('cod_ref') }}" placeholder="Digite o código..." class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>
                        <div>
                            <label for="familia" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Família</label>
                            <select id="familia" name="familia" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todas</option>
                                @foreach($familias as $familia)
                                <option value="{{ $familia }}" {{ request('familia') == $familia ? 'selected' : '' }}>{{ $familia }}</option>
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
                        <a href="{{ route('detalhes_orcamento.index', CryptHelper::encrypt($orcamento->id_orcamento)) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 hover:text-gray-800 transition">
                            <x-icons.reset />
                            Limpar filtros
                        </a>
                    </div>
                </x-filter-card>
            </form>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Itens do orçamento</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">{{ $detalhesOrcamento->total() }} item(ns)</span>
                </div>
            </div>

            @if($detalhesOrcamento->isEmpty())
            @if(request('produto') || request('categoria') || request('cod_ref') || request('familia'))
            <x-empty-state
                title="Nenhum detalhe encontrado"
                message="Não existem itens correspondentes aos filtros informados."
                route="detalhes_orcamento.index"
                :route-params="CryptHelper::encrypt($orcamento->id_orcamento)"
                button-text="Limpar filtros" />
            @else
            @if(!$orcamentoBloqueado)
            <x-empty-state
                title="Nenhum detalhe cadastrado"
                message="Ainda não existem itens cadastrados neste orçamento."
                route="detalhes_orcamento.create"
                :route-params="['id' => CryptHelper::encrypt($orcamento->id_orcamento),'return_url' => request()->fullUrl(),]"
                button-text="Cadastrar detalhe" />
            @else
            <x-empty-state
                title="Nenhum detalhe cadastrado"
                message="Ainda não existem itens cadastrados neste orçamento."
                route="detalhes_orcamento.index"
                :route-params="CryptHelper::encrypt($orcamento->id_orcamento)"
                button-text="Voltar" />
            @endif
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Produto</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Cód.</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Categoria</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Tam.</th>
                                <th class="px-5 py-4 text-right text-[11px] font-bold text-white uppercase tracking-wider">Qtd.</th>
                                <th class="px-5 py-4 text-right text-[11px] font-bold text-white uppercase tracking-wider">Valor Unit.</th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($detalhesOrcamento as $detalhe)
                            <tr class="group transition hover:bg-orange-50/40">
                                <td class="px-5 py-5">
                                    <span class="text-sm font-bold text-gray-800">{{ $detalhe->det_nome ?? $detalhe->produto->prod_nome ?? 'N/A' }}</span>
                                </td>
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $detalhe->det_cod ?: 'N/D' }}</span>
                                </td>
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $detalhe->det_categoria ?: 'N/D' }}</span>
                                </td>
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $detalhe->det_tamanho ?: 'N/D' }}</span>
                                </td>
                                <td class="px-5 py-5 text-right whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $detalhe->det_quantidade }}</span>
                                </td>
                                <td class="px-5 py-5 text-right whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">R$ {{ number_format($detalhe->det_valor_unit, 2, ',', '.') }}</span>
                                </td>

                                <td class="px-5 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :show-route="route('detalhes_orcamento.show', [
                                                'id' => CryptHelper::encrypt($detalhe->id_det),
                                                'return_url' => request()->fullUrl(),
                                            ])"
                                        show-text="Ver"
                                        :edit-route="!$orcamentoBloqueado ? route('detalhes_orcamento.edit', [
                                            'detalhes_orcamento' => CryptHelper::encrypt($detalhe->id_det),
                                            'return_url' => request()->fullUrl(),
                                        ]) : null"
                                        edit-text="Editar"
                                        :delete-action="!$orcamentoBloqueado ? route('detalhes_orcamento.destroy', CryptHelper::encrypt($detalhe->id_det)) : null"
                                        delete-id="formExcluirDetalhe{{ $detalhe->id_det }}"
                                        delete-modal="modalExcluirDetalhe"
                                        :budgets-route="route('customizacao.index', CryptHelper::encrypt($detalhe->id_det))"
                                        budgets-text="Customizações"
                                        :budgets-count="$detalhe->customizacoes->count()" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$detalhesOrcamento" />
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirDetalhe"
    titulo="Excluir detalhe do orçamento"
    mensagem="Deseja realmente apagar este detalhe?"
    textoConfirmar="Excluir" />

@push('scripts')
<script>
    (function() {
        const scrollKey = 'detalhes_orcamento.index.scroll';

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
            if (!link) {
                return;
            }

            const deveSalvar = link.closest('tbody') ||
                link.matches('a[data-detalhes-create]') ||
                link.closest('.pagination') ||
                link.href?.includes('detalhes_orcamento');

            if (deveSalvar) {
                sessionStorage.setItem(scrollKey, window.scrollY);
            }
        });
    })();
</script>
@endpush

@endsection