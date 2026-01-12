{{-- resources/views/view_forma_pagamento/index.blade.php --}}
@extends('layouts.app_financeiro')

@section('title', 'Formas de Pagamento')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- HEADER --}}
    @php
    $idFin = array_key_first(request()->query());
    @endphp

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Formas de Pagamento
        </h1>

        <div class="flex space-x-2">
            {{-- VOLTAR --}}
            <a href="{{ route('financeiro.index') }}"
                class="px-4 py-2 text-sm font-medium rounded-md text-white shadow-sm"
                style="background-color: #6B7280;">
                ← Voltar
            </a>

            {{-- NOVA FORMA --}}
            <a href="{{ route('forma_pagamento.create', ['financeiro_id' => $id]) }}"
                class="px-4 py-2 text-sm font-medium rounded-md text-white shadow-sm"
                style="background-color: #EA792D;">
                Nova Forma de Pagamento
            </a>
        </div>
    </div>

    {{-- RESUMO FINANCEIRO --}}
    @if($idFin)
    @php
    $financeiroSelecionado = $financeiros->firstWhere('id_fin', $idFin);
    $formasDoFinanceiro = $formasPagamento->where('financeiro_id_fin', $idFin);
    $valorPago = $formasDoFinanceiro->sum('forma_valor');
    $valorTotal = $financeiroSelecionado->fin_valor_total ?? 0;
    $valorFaltante = max($valorTotal - $valorPago, 0);
    @endphp

    <div class="mb-6 p-4 bg-gray-100 rounded-lg flex justify-around text-center">
        <div>
            <span class="font-bold text-lg text-red-600">Valor Total</span>
            <span class="block text-gray-900 text-lg">R$ {{ number_format($valorTotal, 2, ',', '.') }}</span>
        </div>
        <div>
            <span class="font-bold text-lg text-green-600">Valor Pago</span>
            <span class="block text-gray-900 text-lg">R$ {{ number_format($valorPago, 2, ',', '.') }}</span>
        </div>
        <div>
            <span class="font-bold text-lg text-yellow-600">Faltante</span>
            <span class="block text-gray-900 text-lg">R$ {{ number_format($valorFaltante, 2, ',', '.') }}</span>
        </div>
    </div>
    @endif

    {{-- SUCESSO --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4">
        <strong>Sucesso!</strong> {{ session('success') }}
    </div>
    @endif

    {{-- SEM FORMAS --}}
    @if ($formasPagamento->isEmpty())
    <p class="text-gray-600 text-center py-8">Nenhuma forma de pagamento cadastrada.</p>
    @else

    {{-- TABELA PRINCIPAL --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">ID Financeiro</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Cliente</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Tipo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Prazo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Parcelas</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Valor Parcela</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Competência</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Descrição</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase">Ações</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">

                @foreach ($formasPagamento as $forma)
                <tr>
                    <td class="px-4 py-4 text-sm">{{ $forma->financeiro_id_fin }}</td>
                    <td class="px-4 py-4 text-sm">{{ $forma->financeiro->fin_nome_cliente }}</td>
                    <td class="px-4 py-4 text-sm">{{ $forma->tipoPagamento?->tipo_plano_fin ?? '—' }}</td>
                    <td class="px-4 py-4 text-sm">{{ $forma->forma_prazo }}</td>
                    <td class="px-4 py-4 text-sm">{{ $forma->forma_qtd_parcela }}</td>
                    <td class="px-4 py-4 text-sm">R$ {{ number_format($forma->forma_valor, 2, ',', '.') }}</td>
                    <td class="px-4 py-4 text-sm">{{ $forma->forma_mes }}</td>
                    <td class="px-4 py-4 text-sm">{{ $forma->forma_descricao }}</td>

                    <td class="px-2 py-4 text-center">
                        <div class="flex justify-center space-x-2">

                            {{-- BOTÃO VER PARCELAS --}}
                            @if($forma->forma_prazo === 'Parcelado')
                            <button
                                class="parcelas-btn px-2 py-1 text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                                data-id="{{ $forma->id_forma_pag }}">
                                Ver Parcelas
                            </button>
                            @endif

                            {{-- EXCLUIR --}}
                            <form action="{{ route('forma_pagamento.destroy', $forma->id_forma_pag) }}" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir esta forma de pagamento?');">
                                @csrf
                                @method('DELETE')
                                <button class="px-2 py-1 text-xs rounded-md text-white bg-red-600 hover:bg-red-700">
                                    Excluir
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>

                {{-- LINHA EXPANDIDA DAS PARCELAS --}}
                <tr id="parcelas-{{ $forma->id_forma_pag }}" class="hidden">
                    <td colspan="9" class="bg-blue-50 p-4">

                        <h3 class="text-lg font-bold text-blue-800 mb-3 flex items-center gap-2">
                            Parcelas da Forma de Pagamento

                            <!-- Ícone com tooltip via JS -->
                            <button
                                onclick="alert('Atenção! Não é possível alterar ou excluir parcelas diretamente por aqui.\nPara corrigir, você deve excluir a forma de pagamento e criar uma nova corretamente.');"
                                class="text-white bg-blue-600 hover:bg-blue-700 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold cursor-pointer"
                                title="Informações">
                                ?
                            </button>
                        </h3>


                        @php
                        $parcelas = $forma->detalhes;
                        @endphp

                        @if($parcelas->isEmpty())
                        <p class="text-gray-600 text-sm">Nenhuma parcela cadastrada.</p>
                        @else
                        <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-md">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">Valor</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">Vencimento</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">Situação</th>
                                    <th class="px-4 py-2 text-center text-xs font-bold uppercase">Ações</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @foreach ($parcelas as $parcela)
                                @php
                                $estaVencida = \Carbon\Carbon::parse($parcela->det_forma_data_venc)->isPast();
                                @endphp

                                @php
                                $dataVenc = \Carbon\Carbon::parse($parcela->det_forma_data_venc);
                                $hoje = \Carbon\Carbon::today();
                                $diasAtraso = $dataVenc->diffInDays($hoje, false);

                                $classeVencido = '';

                                // Status que NÃO devem ter cor
                                if (in_array($parcela->det_situacao, ['Pago', 'Quitado'])) {
                                $classeVencido = '';
                                }
                                elseif ($diasAtraso > 0 && $diasAtraso <= 3) {
                                    // Amarelo (atraso leve)
                                    $classeVencido='bg-yellow-200 text-yellow-800 font-semibold' ;
                                    }
                                    elseif ($diasAtraso> 3) {
                                    // Vermelho (atraso grave)
                                    $classeVencido = 'bg-red-300 text-red-800 font-bold';
                                    }
                                    @endphp



                                    <tr class="{{ $classeVencido }}">
                                        <td class="px-4 py-2 text-sm">
                                            R$ {{ number_format($parcela->det_forma_valor_parcela, 2, ',', '.') }}
                                        </td>

                                        <td class="px-4 py-2 text-sm">
                                            {{ \Carbon\Carbon::parse($parcela->det_forma_data_venc)->format('d/m/Y') }}
                                        </td>

                                        <td class="px-4 py-2 text-sm">
                                            {{ $parcela->det_situacao }}
                                        </td>

                                        <td class="px-4 py-2 text-center">

                                            @if($parcela->det_situacao === 'Pago' ||$parcela->det_situacao === 'Quitado' )

                                            <form action="{{ route('parcelas.voltarNaoPago', $parcela->id_det_forma) }}" method="POST">
                                                @csrf
                                                <button
                                                    class="px-2 py-1 text-xs rounded-md text-white"
                                                    style="background-color: #f59e0b;"
                                                    onclick="return confirm('Deseja marcar esta parcela como NÃO paga?');">
                                                    Voltar para Não Pago
                                                </button>
                                            </form>
                                            @endif
                                            
                                            @if($parcela->det_situacao === 'Não pago' || $parcela->det_situacao === 'Acordo' && $diasAtraso<= 3 )

                                            <form action="{{ route('parcelas.darBaixa', $parcela->id_det_forma) }}" method="POST">
                                                @csrf
                                                <button
                                                    class="px-2 py-1 text-xs rounded-md text-white"
                                                    style="background-color: #22c55e;"
                                                    onclick="return confirm('Confirmar baixa desta parcela?');">
                                                    Dar Baixa
                                                </button>
                                            </form>

                                            @endif


                                        </td>
                                    </tr>


                                    @endforeach
                            </tbody>


                        </table>
                        @endif
                    </td>
                </tr>

                @endforeach

            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- SCRIPT VER PARCELAS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.parcelas-btn').forEach(function(btn) {

            btn.addEventListener('click', function() {

                const id = btn.dataset.id;

                // Fechar TODAS as outras linhas
                document.querySelectorAll('[id^="parcelas-"]').forEach(function(row) {
                    if (row.id !== 'parcelas-' + id) {
                        row.classList.add('hidden');
                    }
                });

                // Alternar a linha clicada
                const row = document.getElementById('parcelas-' + id);
                if (row) row.classList.toggle('hidden');

            });
        });

    });
</script>

@endsection