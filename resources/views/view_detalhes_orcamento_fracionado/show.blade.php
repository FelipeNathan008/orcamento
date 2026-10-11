@extends('layouts.app_financeiro')

@section('title', 'Detalhes do Item do Orçamento Fracionado')

@php
use App\Helpers\CryptHelper;

$idOrcamentoFracionado = CryptHelper::encrypt($orcamentoFracionado->id_orcamento_fracionado);
$urlVoltar = route('detalhes_orcamento_fracionado.index', $idOrcamentoFracionado);
@endphp

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header
            title="Detalhes do Item"
            :back-url="$urlVoltar">
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-6 pb-8">
            <x-info-card
                title="Informações do orçamento"
                :name="'Orçamento Fracionado #' . $orcamentoFracionado->id_orcamento_fracionado"
                type="Orçamento Fracionado"
                :fields="[
                    [
                        'label' => 'ID',
                        'value' => $orcamentoFracionado->id_orcamento_fracionado,
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cliente',
                        'value' => $orcamento->clienteOrcamento->clie_orc_nome ?? 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Código da fábrica',
                        'value' => $orcamento->orc_cod_fabrica ?: 'Não informado',
                    ],
                    [
                        'label' => 'Código interno',
                        'value' => $orcamento->orc_cod_interno ?: 'Não informado',
                    ],
                ]" />

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6 mt-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Detalhes do produto</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Informações do item selecionado.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                    <div class="md:col-span-2">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Produto</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800 break-words">
                            {{ $detalheOrcamento->det_nome ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Família</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $detalheOrcamento->det_familia ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Material</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $detalheOrcamento->det_material ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Código</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $detalheOrcamento->det_cod ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Categoria</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $detalheOrcamento->det_categoria ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Modelo</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $detalheOrcamento->det_modelo ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Cor</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $detalheOrcamento->det_cor ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Gênero</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $detalheOrcamento->det_genero ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Tamanho</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $detalheOrcamento->det_tamanho ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Quantidade</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            {{ $detalheOrcamento->det_quantidade }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Valor unitário</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">
                            R$ {{ number_format($detalheOrcamento->det_valor_unit, 2, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Total do item</p>
                        <p class="mt-1 text-lg font-extrabold text-orange-700">
                            R$ {{ number_format($detalheOrcamento->det_quantidade * $detalheOrcamento->det_valor_unit, 2, ',', '.') }}
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Características</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800 break-words">
                            {{ $detalheOrcamento->det_caract ?? 'N/A' }}
                        </p>
                    </div>

                    @if($detalheOrcamento->det_observacao)
                    <div class="md:col-span-2">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Observação</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800 break-words">
                            {{ $detalheOrcamento->det_observacao }}
                        </p>
                    </div>
                    @endif

                    @if($detalheOrcamento->det_anotacao)
                    <div class="md:col-span-2">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Anotação</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800 break-words">
                            {{ $detalheOrcamento->det_anotacao }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-8">
                <div class="flex items-center gap-3 pb-4 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100 text-blue-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Customizações</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Customizações cadastradas para este item.</p>
                    </div>
                </div>

                @if($detalheOrcamento->customizacoes->count() > 0)
                <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto divide-y divide-gray-200">
                            <thead style="background-color:#343A40;">
                                <tr>
                                    <th class="px-3 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th class="px-3 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">
                                        Local
                                    </th>
                                    <th class="px-3 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">
                                        Posição
                                    </th>
                                    <th class="px-3 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">
                                        Tamanho
                                    </th>
                                    <th class="px-3 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">
                                        Descrição
                                    </th>
                                    <th class="px-3 py-3 text-right text-[10px] font-bold text-white uppercase tracking-wider">
                                        Valor
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($detalheOrcamento->customizacoes as $customizacao)
                                <tr class="hover:bg-orange-50/40 transition">
                                    <td class="px-3 py-4">
                                        <span class="text-sm font-semibold text-gray-800">
                                            {{ $customizacao->cust_tipo ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4">
                                        <span class="text-sm text-gray-700">
                                            {{ $customizacao->cust_local ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4">
                                        <span class="text-sm text-gray-700">
                                            {{ $customizacao->cust_posicao ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4">
                                        <span class="text-sm text-gray-700">
                                            {{ $customizacao->cust_tamanho ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4">
                                        <span class="text-sm text-gray-700 break-words">
                                            {{ $customizacao->cust_descricao ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 text-right whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-800">
                                            R$ {{ number_format($customizacao->cust_valor ?? 0, 2, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <div class="bg-orange-50 border border-orange-200 rounded-xl px-6 py-4 text-right">
                        <p class="text-xs font-bold text-orange-700 uppercase tracking-wide">
                            Total das customizações
                        </p>
                        <p class="mt-1 text-2xl font-extrabold text-orange-900">
                            R$ {{ number_format($detalheOrcamento->customizacoes->sum('cust_valor'), 2, ',', '.') }}
                        </p>
                    </div>
                </div>
                @else
                <x-empty-state
                    title="Nenhuma customização cadastrada"
                    message="Este item não possui customizações cadastradas." />
                @endif
            </div>
        </div>
    </div>
</div>
@endsection