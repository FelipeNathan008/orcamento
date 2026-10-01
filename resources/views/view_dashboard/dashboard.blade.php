@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-8">

    @php
    use Carbon\Carbon;
    Carbon::setLocale('pt_BR');

    $mesAtual = Carbon::now('America/Sao_Paulo')->month;
    $mesNome = ucfirst(Carbon::now('America/Sao_Paulo')->translatedFormat('F'));
    @endphp


    <h1 class="text-3xl font-bold text-gray-800">Dashboard de {{$mesNome}}</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">🛍️ Produtos</h2>

            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                <p class="text-sm text-gray-600">Família mais vendida</p>

                <p class="text-xl font-bold text-indigo-700">
                    {{ $familiaMaisVendida->det_familia ?? 'Não houve vendas' }}

                    @if($familiaMaisVendida)
                    ({{ $familiaMaisVendida->total_vendido }}
                    {{ $familiaMaisVendida->total_vendido == 1 ? 'vendido' : 'vendidos' }})
                    @endif
                </p>
            </div>
        </div>

        <a href="{{ route('dashboard.mapa.financeiro', ['mes' => now()->month, 'ano' => now()->year]) }}"
            class="block bg-orange-50 border border-orange-200 rounded-xl p-6 hover:shadow-md hover:brightness-95 transition">

            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold text-orange-600 uppercase tracking-wide">
                        Controle financeiro
                    </p>

                    <h2 class="text-xl font-bold text-gray-800 mt-1">
                        Mapa Financeiro
                    </h2>

                    <p class="text-sm text-gray-600 mt-1">
                        Visualize as parcelas a pagar por dia e acompanhe o mês.
                    </p>
                </div>

                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-orange-100 text-orange-600 shrink-0">
                    <x-icons.calendar class="w-6 h-6" />
                </div>
            </div>

            <div class="flex items-center justify-between mt-4 pt-4 border-t border-orange-200">
                <span class="text-sm font-semibold text-orange-700">
                    {{ ucfirst($mesNome) }}
                </span>

                <span class="inline-flex items-center gap-1 text-sm font-semibold text-orange-700">
                    Acessar mapa
                    <x-icons.arrow-right />
                </span>
            </div>
        </a>
    </div>

    {{-- ORÇAMENTOS --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">
            📊 Orçamentos
        </h2>

        <p class="text-sm text-gray-500 mb-4">
            Clique em um dos cards para acessar a listagem geral com o filtro correspondente.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

            {{-- Orçamentos no mês --}}
            <a href="{{ route('dashboard.orcamentos', ['mes' => $mesAtual]) }}"
                class="block bg-amber-100 border border-amber-400 rounded-lg p-4 hover:shadow-md hover:brightness-95 transition">

                <b class="text-sm text-gray-600">
                    Orçamentos no mês ({{ $mesNome }})
                </b>

                <p class="text-3xl font-bold text-amber-700">
                    {{ $orcamentosMes }}
                </p>
            </a>

            {{-- Pendentes --}}
            <a href="{{ route('dashboard.orcamentos', ['status_query' => 'pendente']) }}"
                class="block bg-amber-100 border border-amber-400 rounded-lg p-4 hover:shadow-md hover:brightness-95 transition">

                <b class="text-sm text-gray-600">
                    Pendente - Total geral
                </b>

                <p class="text-3xl font-bold text-amber-700">
                    {{ $orcamentoPendente }}
                </p>
            </a>

            {{-- Para aprovação --}}
            <a href="{{ route('dashboard.orcamentos', ['status_query' => 'para aprovacao']) }}"
                class="block bg-amber-100 border border-amber-400 rounded-lg p-4 hover:shadow-md hover:brightness-95 transition">

                <b class="text-sm text-gray-600">
                    Para aprovação - Total geral
                </b>

                <p class="text-3xl font-bold text-amber-700">
                    {{ $orcamentoParaAprovacao }}
                </p>
            </a>

            {{-- Aprovados --}}
            <a href="{{ route('dashboard.orcamentos', ['status_query' => 'aprovado','mes' => $mesAtual]) }}"
                class="block bg-emerald-50 border border-emerald-200 rounded-lg p-4 hover:shadow-md hover:brightness-95 transition">

                <b class="text-sm text-gray-600">

                    Aprovados no mês ({{ $mesNome }})
                </b>

                <p class="text-3xl font-bold text-emerald-700">
                    {{ $orcamentoAprovado }}
                </p>
            </a>

            {{-- Finalizados --}}
            <a href="{{ route('dashboard.orcamentos', ['status_query' => 'finalizado', 'mes' => $mesAtual]) }}"
                class="block bg-emerald-50 border border-emerald-200 rounded-lg p-4 hover:shadow-md hover:brightness-95 transition">

                <b class="text-sm text-gray-600">
                    Finalizados no mês ({{ $mesNome }})
                </b>

                <p class="text-3xl font-bold text-emerald-700">
                    {{ $orcamentoFinalizado }}
                </p>
            </a>

            {{-- Valor vendido no mês --}}
            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">

                <b class="text-sm text-gray-600">
                    Valor vendido no mês ({{ $mesNome }})
                </b>

                <p class="text-3xl font-bold text-teal-700">
                    R$ {{ number_format($totalMes, 2, ',', '.') }}
                </p>

            </div>

            {{-- Rejeitados --}}
            <a href="{{ route('dashboard.orcamentos', ['status_query' => 'rejeitado','mes' => $mesAtual]) }}"
                class="block bg-violet-50 border border-violet-200 rounded-lg p-4 hover:shadow-md hover:brightness-95 transition">

                <b class="text-sm text-gray-600">
                    Rejeitados no mês ({{ $mesNome }})
                </b>

                <p class="text-3xl font-bold text-violet-700">
                    {{ $orcamentoRejeitado }}
                </p>
            </a>

            {{-- Atrasados --}}
            <a href="{{ route('dashboard.orcamentos', ['status_query' => 'pendente', 'filtro_vencimento' => 'vencidos']) }}"
                class="block bg-violet-50 border border-violet-200 rounded-lg p-4 hover:shadow-md hover:brightness-95 transition">

                <b class="text-sm text-gray-600">
                    Atrasados (Com status Pendente)
                </b>

                <p class="text-3xl font-bold text-violet-700">
                    {{ $orcamentoAtrasado }}
                </p>
            </a>

            {{-- Espaço restante --}}
            <div class="bg-violet-50 border border-violet-200 rounded-lg p-4"></div>

        </div>

        {{-- ORÇAMENTOS FRACIONADOS --}}
        <h3 class="text-lg font-semibold mb-4 text-gray-700">
            📦 Orçamentos Fracionados
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

            {{-- Fracionados no mês --}}
            <a
                href="{{ route('dashboard.orcamentos.fracionados') }}"
                class="block bg-orange-50 border border-orange-200 rounded-lg p-4 hover:shadow-md hover:brightness-95 transition">

                <b class="text-sm text-gray-600">
                    Fracionados
                </b>

                <p class="text-3xl font-bold text-orange-700">
                    {{ $fracionadosMes }}
                </p>

            </a>

            {{-- Pendentes --}}
            <a
                href="{{ route('dashboard.orcamentos.fracionados', ['status_query' => 'pendente']) }}"
                class="block bg-yellow-50 border border-yellow-200 rounded-lg p-4 hover:shadow-md hover:brightness-95 transition">

                <b class="text-sm text-gray-600">
                    Pendentes - Total geral
                </b>

                <p class="text-3xl font-bold text-yellow-700">
                    {{ $fracionadosPendente }}
                </p>

            </a>

            {{-- Pedido fábrica --}}
            <a
                href="{{ route('dashboard.orcamentos.fracionados', ['status_query' => 'pedido fabrica']) }}"
                class="block bg-purple-50 border border-purple-200 rounded-lg p-4 hover:shadow-md hover:brightness-95 transition">

                <b class="text-sm text-gray-600">
                    Pedido fábrica - Total geral
                </b>

                <p class="text-3xl font-bold text-purple-700">
                    {{ $fracionadosPedidoFabrica }}
                </p>

            </a>

            {{-- Transportadora --}}
            <a
                href="{{ route('dashboard.orcamentos.fracionados', ['status_query' => 'transportadora']) }}"
                class="block bg-indigo-50 border border-indigo-200 rounded-lg p-4 hover:shadow-md hover:brightness-95 transition">

                <b class="text-sm text-gray-600">
                    Transportadora - Total geral
                </b>

                <p class="text-3xl font-bold text-indigo-700">
                    {{ $fracionadosTransportadora }}
                </p>

            </a>

            {{-- Entregues --}}
            <a
                href="{{ route('dashboard.orcamentos.fracionados', ['status_query' => 'entregue']) }}"
                class="block bg-green-50 border border-green-200 rounded-lg p-4 hover:shadow-md hover:brightness-95 transition">

                <b class="text-sm text-gray-600">
                    Entregues - Total geral
                </b>

                <p class="text-3xl font-bold text-green-700">
                    {{ $fracionadosEntregue }}
                </p>

            </a>

        </div>

        {{-- Fluxo dos Status --}}
        <h3 class="text-lg font-semibold mb-4 text-gray-700">
            📊 Fluxo dos Status (quantidade)
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-4">

            {{-- Aguardando pagamento --}}
            <a
                href="{{ route('financeiro.index', ['status' => 'Aguardando pagamento']) }}"
                class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center gap-2 hover:shadow-md hover:bg-gray-50 transition">

                <img
                    src="/imagens_status/11.png"
                    class="w-16 h-16 object-contain"
                    alt="Aguardando pagamento">

                <b class="text-sm text-gray-700 text-center">
                    Aguardando pagamento
                </b>

            </a>

            {{-- Pagamento realizado --}}
            <a
                href="{{ route('financeiro.index', ['status' => 'Pagamento realizado']) }}"
                class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center gap-2 hover:shadow-md hover:bg-gray-50 transition">

                <img
                    src="/imagens_status/21.png"
                    class="w-16 h-16 object-contain"
                    alt="Pagamento realizado">

                <b class="text-sm text-gray-700 text-center">
                    Pagamento realizado
                </b>

            </a>

            {{-- Análise pedido --}}
            <a
                href="{{ route('financeiro.index', ['status' => 'Análise pedido']) }}"
                class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center gap-2 hover:shadow-md hover:bg-gray-50 transition">

                <img
                    src="/imagens_status/31.png"
                    class="w-16 h-16 object-contain"
                    alt="Análise pedido">

                <b class="text-sm text-gray-700 text-center">
                    Análise pedido
                </b>

            </a>

            {{-- Pedido fábrica --}}
            <a
                href="{{ route('financeiro.index', ['status' => 'Pedido fábrica']) }}"
                class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center gap-2 hover:shadow-md hover:bg-gray-50 transition">

                <img
                    src="/imagens_status/41.png"
                    class="w-16 h-16 object-contain"
                    alt="Pedido fábrica">

                <b class="text-sm text-gray-700 text-center">
                    Pedido fábrica
                </b>

            </a>

            {{-- Transportadora --}}
            <a
                href="{{ route('financeiro.index', ['status' => 'Transportadora']) }}"
                class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center gap-2 hover:shadow-md hover:bg-gray-50 transition">

                <img
                    src="/imagens_status/51.png"
                    class="w-16 h-16 object-contain"
                    alt="Transportadora">

                <b class="text-sm text-gray-700 text-center">
                    Transportadora
                </b>

            </a>

            {{-- Entregue --}}
            <a
                href="{{ route('financeiro.index', ['status' => 'Entregue']) }}"
                class="bg-white border border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center gap-2 hover:shadow-md hover:bg-gray-50 transition">

                <img
                    src="/imagens_status/61.png"
                    class="w-16 h-16 object-contain"
                    alt="Entregue">

                <b class="text-sm text-gray-700 text-center">
                    Entregue
                </b>

            </a>

        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-6 gap-4">

            {{-- Quantidade aguardando pagamento --}}
            <a
                href="{{ route('financeiro.index', ['status' => 'Aguardando pagamento']) }}"
                class="bg-white border border-gray-300 rounded-lg p-4 flex items-center justify-center hover:shadow-md hover:bg-gray-50 transition">

                <p class="text-3xl font-bold text-black-700">
                    {{ $statusUm }}
                </p>

            </a>

            {{-- Quantidade pagamento realizado --}}
            <a
                href="{{ route('financeiro.index', ['status' => 'Pagamento realizado']) }}"
                class="bg-white border border-gray-300 rounded-lg p-4 flex items-center justify-center hover:shadow-md hover:bg-gray-50 transition">

                <p class="text-3xl font-bold text-black-700">
                    {{ $statusDois }}
                </p>

            </a>

            {{-- Quantidade análise pedido --}}
            <a
                href="{{ route('financeiro.index', ['status' => 'Análise pedido']) }}"
                class="bg-white border border-gray-300 rounded-lg p-4 flex items-center justify-center hover:shadow-md hover:bg-gray-50 transition">

                <p class="text-3xl font-bold text-black-700">
                    {{ $statusTres }}
                </p>

            </a>

            {{-- Quantidade pedido fábrica --}}
            <a
                href="{{ route('financeiro.index', ['status' => 'Pedido fábrica']) }}"
                class="bg-white border border-gray-300 rounded-lg p-4 flex items-center justify-center hover:shadow-md hover:bg-gray-50 transition">

                <p class="text-3xl font-bold text-black-700">
                    {{ $statusQuatro }}
                </p>

            </a>

            {{-- Quantidade transportadora --}}
            <a
                href="{{ route('financeiro.index', ['status' => 'Transportadora']) }}"
                class="bg-white border border-gray-300 rounded-lg p-4 flex items-center justify-center hover:shadow-md hover:bg-gray-50 transition">

                <p class="text-3xl font-bold text-black-700">
                    {{ $statusCinco }}
                </p>

            </a>

            {{-- Quantidade entregue --}}
            <a
                href="{{ route('financeiro.index', ['status' => 'Entregue']) }}"
                class="bg-white border border-gray-300 rounded-lg p-4 flex items-center justify-center hover:shadow-md hover:bg-gray-50 transition">

                <p class="text-3xl font-bold text-black-700">
                    {{ $statusSeis }}
                </p>

            </a>

        </div>
    </div>

    {{-- FINANCEIRO --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">💰 Financeiro</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('dashboard.parcelas.financeiro', ['filtro' => 'atrasadas']) }}"
                class="block bg-red-50 border border-red-200 rounded-lg p-4 hover:shadow-md hover:brightness-95 transition">
                <b class="text-sm text-gray-600">
                    Atrasados / inadimplentes - Total geral
                </b>
                <p class="text-3xl font-bold text-red-700">
                    {{ $financeiroAtraso }}
                </p>
            </a>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <b class="text-sm text-gray-600">Valor geral (atrasados / inadimplentes)</b>
                <p class="text-3xl font-bold text-red-700"> R$ {{ number_format($valorAtrasoNaoPago, 2, ',', '.') }}</p>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <b class="text-sm text-gray-600">Pagos no mês ({{ $mesNome }})</b>
                <p class="text-3xl font-bold text-blue-700">{{ $financeiroPago}}</p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <b class="text-sm text-gray-600">Quitados no mês ({{ $mesNome }})</b>
                <p class="text-3xl font-bold text-blue-700">{{ $financeiroQuitado}}</p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <b class="text-sm text-gray-600">Acordo no mês ({{ $mesNome }})</b>
                <p class="text-3xl font-bold text-blue-700">{{ $financeiroQuitado}}</p>
            </div>

            <div class="bg-lime-50 border border-lime-200 rounded-lg p-4">
                <b class="text-sm text-gray-600">Previsão do dia (a receber) - {{ now()->format('d/m/Y') }}</b>

                <p class="text-3xl font-bold text-teal-700">R$ {{ number_format($previsaoDoDia, 2, ',', '.') }}</p>
            </div>

            <div class="bg-lime-50 border border-lime-200 rounded-lg p-4">
                <b class="text-sm text-gray-600">
                    Valor vencendo hoje (PAGOS) - {{ now()->format('d/m/Y') }}
                </b>
                <p class="text-3xl font-bold text-teal-700">
                    R$ {{ number_format($previsaoDoDiaPago, 2, ',', '.') }}
                </p>
            </div>

            <div class="bg-lime-50 border border-lime-200 rounded-lg p-4">
                <b class="text-sm text-gray-600">Valor acumulado no mês ({{ $mesNome }})</b>
                <p class="text-3xl font-bold text-teal-700"> R$ {{ number_format($valorTotalMes, 2, ',', '.') }}</p>
            </div>

        </div>
    </div>

</div>

@endsection