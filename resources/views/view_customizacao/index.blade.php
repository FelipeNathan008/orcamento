@extends('layouts.app')

@section('title', 'Customizações')

@php
use App\Helpers\CryptHelper;

$orcamento = $detalhe->orcamento;
$cliente = $orcamento->clienteOrcamento;

$orcamentoBloqueado = in_array(
strtolower(trim($orcamento->orc_status)),
['aprovado', 'finalizado', 'rejeitado']
);

$quantidade = (int) ($detalhe->det_quantidade ?? 0);
$totalItem = $quantidade * (float) ($detalhe->det_valor_unit ?? 0);
$valorUnitarioCustomizacoes = $detalhe->customizacoes->sum(
fn($customizacao) => (float) ($customizacao->cust_valor ?? 0)
);
$totalCustomizacoes = $quantidade * $valorUnitarioCustomizacoes;
$totalGeral = $totalItem + $totalCustomizacoes;
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header
            title="Customizações do Produto"
            :back-url="$urlDetalhesOrcamento">
            @if(!$orcamentoBloqueado)
            <x-header-action href="{{ route('customizacao.create', [
                    'id' => CryptHelper::encrypt($detalhe->id_det),
                    'return_url' => request()->fullUrl(),
                ]) }}" data-customizacao-create>
                Nova Customização
            </x-header-action>
            @endif
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <x-info-card
                title="Informações do Produto"
                :name="'Produto: ' . ($detalhe->det_nome).' | Código: '. ($detalhe->det_cod)"
                type="Produto"
                :fields="[
                    [
                        'label' => 'Cód. Interno',
                        'value' => $detalhe->orcamento->orc_cod_interno ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cód. Fábrica',
                        'value' => $detalhe->orcamento->orc_cod_fabrica ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cliente',
                        'value' => $cliente->clie_orc_nome ?? 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Código',
                        'value' => $detalhe->det_cod ?: 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Categoria',
                        'value' => $detalhe->det_categoria ?: 'Não informado',
                    ],
                    [
                        'label' => 'Cor / Tamanho',
                        'value' => trim(($detalhe->det_cor ?: 'N/I') . ' / ' . ($detalhe->det_tamanho ?: 'N/I')),
                    ],
                    [
                        'label' => 'Características',
                        'value' => $detalhe->det_caract ?: 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Quantidade',
                        'value' => $detalhe->det_quantidade ?? '0',
                    ],
                ]" />

            <p class="mt-4 text-xs text-yellow-600">
                Os valores abaixo consideram o produto selecionado.
            </p>

            <div class="mt-4">
                <x-summary-cards :cards="[
                    [
                        'title' => 'Total dos Itens',
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
            </div>

            <form method="GET" action="{{ route('customizacao.index', CryptHelper::encrypt($detalhe->id_det)) }}" class="mt-6">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="tipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tipo</label>
                            <select id="tipo" name="tipo" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todos</option>
                                @foreach($tipos as $tipo)
                                <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="local" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Local</label>
                            <select id="local" name="local" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todos</option>
                                @foreach($locais as $local)
                                <option value="{{ $local }}" {{ request('local') == $local ? 'selected' : '' }}>{{ $local }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="posicao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Posição</label>
                            <select id="posicao" name="posicao" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todas</option>
                                @foreach($posicoes as $posicao)
                                <option value="{{ $posicao }}" {{ request('posicao') == $posicao ? 'selected' : '' }}>{{ $posicao }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="formatacao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Formatação</label>
                            <select id="formatacao" name="formatacao" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todas</option>
                                @foreach($formatacoes as $formatacao)
                                <option value="{{ $formatacao }}" {{ request('formatacao') == $formatacao ? 'selected' : '' }}>{{ $formatacao }}</option>
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
                        <a href="{{ route('customizacao.index', CryptHelper::encrypt($detalhe->id_det)) }}"
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
                <h2 class="text-lg font-bold text-gray-800">Customizações cadastradas</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">{{ $customizacoes->count() }} customização(ões)</span>
                </div>
            </div>

            @if($customizacoes->isEmpty())
            @if(request('tipo') || request('local') || request('posicao') || request('formatacao'))
            <x-empty-state
                title="Nenhuma customização encontrada"
                message="Não existem customizações correspondentes aos filtros informados."
                route="customizacao.index"
                :route-params="CryptHelper::encrypt($detalhe->id_det)"
                button-text="Limpar filtros" />
            @else
            @if(!$orcamentoBloqueado)
            <x-empty-state
                title="Nenhuma customização cadastrada"
                message="Ainda não existem customizações cadastradas para este produto."
                route="customizacao.create"
                :route-params="CryptHelper::encrypt($detalhe->id_det)"
                button-text="Cadastrar customização" />
            @else
            <x-empty-state
                title="Nenhuma customização cadastrada"
                message="Ainda não existem customizações cadastradas para este produto."
                route="customizacao.index"
                :route-params="CryptHelper::encrypt($detalhe->id_det)"
                button-text="Voltar" />
            @endif
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
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($customizacoes as $customizacao)
                            <tr class="group transition hover:bg-orange-50/40">
                                <td class="px-5 py-5">
                                    <span class="text-sm font-semibold text-gray-800">{{ $customizacao->cust_tipo }}</span>
                                </td>
                                <td class="px-5 py-5">
                                    <span class="text-sm text-gray-700">{{ $customizacao->cust_local }}</span>
                                </td>
                                <td class="px-5 py-5">
                                    <span class="text-sm text-gray-700">{{ $customizacao->cust_posicao }}</span>
                                </td>
                                <td class="px-5 py-5">
                                    <span class="text-sm text-gray-700">{{ $customizacao->cust_formatacao }}</span>
                                </td>
                                <td class="px-5 py-5 text-right whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">R$ {{ number_format($customizacao->cust_valor, 2, ',', '.') }}</span>
                                </td>
                                <td class="px-5 py-5 text-center">
                                    @if($customizacao->cust_imagem)
                                    <img src="{{ asset('images_customizacoes/' . $customizacao->cust_imagem) }}" class="w-14 h-14 object-cover rounded-lg border border-gray-200 shadow-sm mx-auto">
                                    @else
                                    <span class="text-xs text-gray-500">Sem imagem</span>
                                    @endif
                                </td>
                                <td class="px-5 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :layout-route="route('customizacao.camisa', [
                                                'id' => CryptHelper::encrypt($customizacao->id_customizacao),
                                                'return_url' => request()->fullUrl(),
                                            ])"
                                        layout-text="Layout"
                                        :show-route="route('customizacao.show', [
                                            'customizacao' => CryptHelper::encrypt($customizacao->id_customizacao),
                                        ])"
                                        show-text="Ver"
                                        :edit-route="!$orcamentoBloqueado ? route('customizacao.edit', [
                                            'customizacao' => CryptHelper::encrypt($customizacao->id_customizacao),
                                        ]) : null"
                                        edit-text="Editar"
                                        :delete-action="!$orcamentoBloqueado ? route('customizacao.destroy', [
                                            'customizacao' => CryptHelper::encrypt($customizacao->id_customizacao),
                                        ]) : null"
                                        delete-id="formExcluirCustomizacao{{ $customizacao->id_customizacao }}"
                                        delete-modal="modalExcluirCustomizacao" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirCustomizacao"
    titulo="Excluir customização"
    mensagem="Deseja realmente apagar esta customização?"
    textoConfirmar="Excluir" />

@push('scripts')
<script>
    (function() {
        const scrollKey = 'customizacao.index.scroll';
        const savedScroll = sessionStorage.getItem(scrollKey);

        if (savedScroll !== null) {
            window.scrollTo(0, parseInt(savedScroll, 10));
            sessionStorage.removeItem(scrollKey);
        }

        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (!link) {
                return;
            }

            const href = link.getAttribute('href');

            if (
                link.closest('tbody') ||
                link.closest('thead') ||
                link.matches('a[data-customizacao-create]') ||
                href?.includes('/create') ||
                href?.includes('customizacao')
            ) {
                sessionStorage.setItem(scrollKey, window.scrollY);
            }
        });
    })();
</script>
@endpush

@endsection