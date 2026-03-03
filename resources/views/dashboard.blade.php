@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-8">

    @php
    use Carbon\Carbon;
    Carbon::setLocale('pt_BR');

    $mesAtual = Carbon::now('America/Sao_Paulo')->month;
    $mesNome = Carbon::now('America/Sao_Paulo')->translatedFormat('F');
    @endphp


    <h1 class="text-3xl font-bold text-gray-800">Dashboard de {{$mesNome}}</h1>
    {{-- PRODUTOS --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">🛍️ Produtos</h2>

        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
            <p class="text-sm text-gray-600">Família mais vendida </p>
            <p class="text-xl font-bold text-indigo-700">
                {{ $familiaMaisVendida->prod_familia ?? 'Não houve vendas' }}
                ({{ $familiaMaisVendida->total_vendido ?? 0 }} vendidos)
            </p>
        </div>
    </div>

    {{-- ORÇAMENTOS --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">📊 Orçamentos</h2>

        {{-- Cards de Orçamentos --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

            <div class="bg-amber-100 border border-amber-400 rounded-lg p-4">
                <b class="text-sm text-gray-600">Orçamentos no mês ({{ $mesNome }})</b>
                <p class="text-3xl font-bold text-amber-700">{{ $orcamentosMes }}</p>
            </div>

            <div class="bg-amber-100 border border-amber-400 rounded-lg p-4">
                <b class="text-sm text-gray-600">Pendente - Total geral</b>
                <p class="text-3xl font-bold text-amber-700">{{ $orcamentoPendente }}</p>
            </div>

            <div class="bg-amber-100 border border-amber-400 rounded-lg p-4">
                <b class="text-sm text-gray-600">Para aprovação - Total geral</b>
                <p class="text-3xl font-bold text-amber-700">{{ $orcamentoParaAprovacao }}</p>
            </div>

            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Aprovados (não finalizados)</p>
                <p class="text-3xl font-bold text-emerald-700">{{ $orcamentoAprovado }}</p>
            </div>

            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Finalizados no mês</p>
                <p class="text-3xl font-bold text-emerald-700">{{ $orcamentoFinalizado }}</p>
            </div>

            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Valor vendido no mês</p>
                <p class="text-3xl font-bold text-teal-700">
                    R$ {{ number_format($totalMes, 2, ',', '.') }}
                </p>
            </div>

            <div class="bg-violet-50 border border-violet-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Rejeitados no mês</p>
                <p class="text-3xl font-bold text-violet-700">{{ $orcamentoRejeitado }}</p>
            </div>

            <div class="bg-violet-50 border border-violet-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Atrasados (Com status Pendente)</p>
                <p class="text-3xl font-bold text-violet-700">{{ $orcamentoAtrasado }}</p>
            </div>

            <div class="bg-violet-50 border border-violet-200 rounded-lg p-4"></div>
        </div>

        {{-- Fluxo dos Status --}}
        <h3 class="text-lg font-semibold mb-4 text-gray-700">📊 Fluxo dos Status (quantidade)</h3>

        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-4">

            <div class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center gap-2">
                <img src="/imagens_status/11.png" class="w-16 h-16 object-contain" alt="Status 1">
                <p class="text-sm text-gray-700 text-center">Aguardando pagamento</p>
            </div>

            <div class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center gap-2">
                <img src="/imagens_status/21.png" class="w-16 h-16 object-contain" alt="Status 2">
                <p class="text-sm text-gray-700 text-center">Pagamento realizado</p>
            </div>

            <div class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center gap-2">
                <img src="/imagens_status/31.png" class="w-16 h-16 object-contain" alt="Status 3">
                <p class="text-sm text-gray-700 text-center">Análise pedido</p>
            </div>

            <div class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center gap-2">
                <img src="/imagens_status/41.png" class="w-16 h-16 object-contain" alt="Status 4">
                <p class="text-sm text-gray-700 text-center">Pedido fábrica</p>
            </div>

            <div class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center gap-2">
                <img src="/imagens_status/51.png" class="w-16 h-16 object-contain" alt="Status 5">
                <p class="text-sm text-gray-700 text-center">Transportadora</p>
            </div>

            <div class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center gap-2">
                <img src="/imagens_status/61.png" class="w-16 h-16 object-contain" alt="Status 6">
                <p class="text-sm text-gray-700 text-center">Entregue</p>
            </div>

        </div>


        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white border border-gray-300 rounded-lg p-4 flex items-center justify-center">
                <p class="text-3xl font-bold text-black-700">{{ $statusUm }}</p>
            </div>
            <div class="bg-white border border-gray-300 rounded-lg p-4 flex items-center justify-center">
                <p class="text-3xl font-bold text-black-700">{{ $statusDois }}</p>
            </div>
            <div class="bg-white border border-gray-300 rounded-lg p-4 flex items-center justify-center">
                <p class="text-3xl font-bold text-black-700">{{ $statusTres }}</p>
            </div>
            <div class="bg-white border border-gray-300 rounded-lg p-4 flex items-center justify-center">
                <p class="text-3xl font-bold text-black-700">{{ $statusQuatro }}</p>
            </div>
            <div class="bg-white border border-gray-300 rounded-lg p-4 flex items-center justify-center">
                <p class="text-3xl font-bold text-black-700">{{ $statusCinco }}</p>
            </div>
            <div class="bg-white border border-gray-300 rounded-lg p-4 flex items-center justify-center">
                <p class="text-3xl font-bold text-black-700">{{ $statusSeis }}</p>
            </div>
        </div>



    </div>

    {{-- FINANCEIRO --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">💰 Financeiro</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Parcelas a pagar (no mês)</p>
                <p class="text-3xl font-bold text-red-700">{{ $financeiroPagar }}</p>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Atrasados / inadimplentes</p>
                <p class="text-3xl font-bold text-red-700">{{ $financeiroAtraso }}</p>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Valor geral (atrasados / inadimplentes)</p>
                <p class="text-3xl font-bold text-red-700"> R$ {{ number_format($valorAtrasoNaoPago, 2, ',', '.') }}
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Pagos</p>
                <p class="text-3xl font-bold text-blue-700">{{ $financeiroPago}}</p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Quitados</p>
                <p class="text-3xl font-bold text-blue-700">{{ $financeiroQuitado}}</p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <b class="text-sm text-gray-600">Previsão do dia (a receber)</b>
                <p class="text-3xl font-bold text-blue-700"> R$ {{ number_format($previsaoDoDia,2,',','.')}}</p>
            </div>

            <div class="bg-lime-50 border border-lime-200 rounded-lg p-4">
            </div>

            <div class="bg-lime-50 border border-lime-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Valor vencendo hoje (PAGOS)</p>
                <p class="text-3xl font-bold text-teal-700"> R$ {{ number_format($previsaoDoDiaPago, 2, ',', '.') }}</p>
            </div>

            <div class="bg-lime-50 border border-lime-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Valor acumulado (no mês)</p>
                <p class="text-3xl font-bold text-teal-700"> R$ {{ number_format($valorTotalMes, 2, ',', '.') }}</p>
            </div>



        </div>
    </div>

</div>

@endsection