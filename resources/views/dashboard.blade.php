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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            <div class="bg-sky-50 border border-sky-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Orçamentos no mês ({{ $mesNome }})</p>
                <p class="text-3xl font-bold text-sky-700">{{ $orcamentosMes }}</p>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Em aberto (Para aprovação)</p>
                <p class="text-3xl font-bold text-amber-700">{{ $orcamentoParaAprovacao }}</p>
            </div>

            <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Aprovados (não finalizados)</p>
                <p class="text-3xl font-bold text-cyan-700">{{ $orcamentoAprovado }}</p>
            </div>

            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Finalizados</p>
                <p class="text-3xl font-bold text-emerald-700">{{ $orcamentoFinalizado }}</p>
            </div>

            <div class="bg-violet-50 border border-violet-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Rejeitados</p>
                <p class="text-3xl font-bold text-violet-700">{{ $orcamentoRejeitado }}</p>
            </div>

            <div class="bg-rose-50 border border-rose-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Atrasados</p>
                <p class="text-3xl font-bold text-rose-700">{{ $orcamentoAtrasado }}</p>
            </div>

            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Valor vendido no mês</p>
                <p class="text-3xl font-bold text-teal-700"> R$ {{ number_format($totalMes, 2, ',', '.') }}
                </p>
            </div>

        </div>
    </div>

    {{-- FINANCEIRO --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">💰 Financeiro</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Parcelas a pagar</p>
                <p class="text-3xl font-bold text-orange-700">{{ $financeiroPagar }}</p>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Atrasados</p>
                <p class="text-3xl font-bold text-red-700">{{ $financeiroAtraso }}</p>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Pagos</p>
                <p class="text-3xl font-bold text-green-700">{{ $financeiroPago}}</p>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Quitados</p>
                <p class="text-3xl font-bold text-green-700">{{ $financeiroQuitado}}</p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Previsão do dia</p>
                <p class="text-3xl font-bold text-blue-700">{{ $previsaoDoDia}}</p>
            </div>

            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Valor recebido</p>
                <p class="text-3xl font-bold text-emerald-700"></p>{{$previsaoDoDiaNaoPago}}</p>
            </div>


            <div class="bg-lime-50 border border-lime-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Previsão do dia (pagos)</p>
                <p class="text-3xl font-bold text-lime-700">{{ $previsaoDoDiaPago}}</p>
            </div>

        </div>
    </div>

</div>

@endsection