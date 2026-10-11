
@extends('layouts.app')

@section('title', 'Produtos Mais Vendidos')

@section('content')
@php
    $cores = ['#EA792D', '#3B82F6', '#8B5CF6', '#10B981', '#F59E0B', '#EC4899', '#06B6D4', '#64748B'];

    $codigosGrafico = $rankingCodigos->take(10)->values();
    $familiasGrafico = $rankingFamilias->take(5)->values();
    $categoriasGrafico = $rankingCategorias->take(8)->values();

    $maxCodigo = max((float) ($codigosGrafico->max('total_vendido') ?? 0), 1);
    $maxCategoria = max((float) ($categoriasGrafico->max('total_vendido') ?? 0), 1);

    $totalFamiliasGrafico = (float) $familiasGrafico->sum('total_vendido');
    $totalOutrasFamilias = (float) $rankingFamilias->skip(5)->sum('total_vendido');

    $totalRosca = $totalFamiliasGrafico + $totalOutrasFamilias;
    $totalRosca = max($totalRosca, 1);

    $segmentosRosca = [];
    $inicioRosca = 0;

    foreach ($familiasGrafico as $i => $familia) {
        $percentual = ((float) $familia->total_vendido / $totalRosca) * 100;
        $fimRosca = $inicioRosca + $percentual;

        $segmentosRosca[] = [
            'nome' => $familia->det_familia,
            'total' => (float) $familia->total_vendido,
            'percentual' => $percentual,
            'cor' => $cores[$i % count($cores)],
            'inicio' => $inicioRosca,
            'fim' => $fimRosca,
        ];

        $inicioRosca = $fimRosca;
    }

    if ($totalOutrasFamilias > 0) {
        $percentual = ($totalOutrasFamilias / $totalRosca) * 100;

        $segmentosRosca[] = [
            'nome' => 'Outras famílias',
            'total' => $totalOutrasFamilias,
            'percentual' => $percentual,
            'cor' => '#CBD5E1',
            'inicio' => $inicioRosca,
            'fim' => 100,
        ];
    }

    $fatiaRosca = [];

    foreach ($segmentosRosca as $segmento) {
        $fatiaRosca[] = $segmento['cor'] . ' ' . $segmento['inicio'] . '% ' . $segmento['fim'] . '%';
    }

    $fundoRosca = count($fatiaRosca)
        ? 'conic-gradient(' . implode(', ', $fatiaRosca) . ')'
        : '#E5E7EB';

    $pontosLinha = [];
    $maxPontos = max($categoriasGrafico->count() - 1, 1);
    $larguraGrafico = 700;
    $alturaGrafico = 250;
    $margemX = 35;
    $margemY = 25;
    $areaAltura = $alturaGrafico - ($margemY * 2);

    foreach ($categoriasGrafico as $i => $categoria) {
        $x = $margemX + ($i / $maxPontos) * ($larguraGrafico - ($margemX * 2));
        $y = $alturaGrafico - $margemY - (((float) $categoria->total_vendido / $maxCategoria) * $areaAltura);

        $pontosLinha[] = [
            'x' => $x,
            'y' => $y,
            'nome' => $categoria->det_categoria,
            'total' => (float) $categoria->total_vendido,
        ];
    }

    $coordenadasLinha = collect($pontosLinha)
        ->map(fn ($ponto) => $ponto['x'] . ',' . $ponto['y'])
        ->implode(' ');

    $coordenadasArea = count($pontosLinha)
        ? $margemX . ',' . ($alturaGrafico - $margemY) . ' ' . $coordenadasLinha . ' ' . ($larguraGrafico - $margemX) . ',' . ($alturaGrafico - $margemY)
        : '';

    $meses = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março',
        4 => 'Abril', 5 => 'Maio', 6 => 'Junho',
        7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro',
        10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
    ];
@endphp

<x-alert-flash />

<div class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8 font-poppins">
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('dashboard') }}" class="mb-3 inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-[#EA792D]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Voltar ao dashboard
            </a>

            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">Produtos Mais Vendidos</h1>
            <p class="mt-1 text-sm text-gray-500">Analise os produtos, famílias e categorias com maior volume de vendas.</p>
        </div>

        <div class="flex items-center gap-2 self-start rounded-xl border border-orange-100 bg-orange-50 px-4 py-3 sm:self-center">
            <div class="rounded-lg bg-white p-2 text-[#EA792D] shadow-sm">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3v18h18M8 15l4-4 4 3 5-7"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-orange-700">Período selecionado</p>
                <p class="text-sm font-bold text-gray-900">{{ $meses[$mes] ?? $mes }} de {{ $ano }}</p>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('dashboard.produtos.mais-vendidos') }}" class="mb-7 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-center gap-2">
            <svg class="h-5 w-5 text-[#EA792D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 9h12m-9 5h6m-4 5h2"/>
            </svg>
            <h2 class="font-semibold text-gray-800">Filtrar período</h2>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 sm:items-end">
            <div>
                <label for="mes" class="mb-1.5 block text-sm font-medium text-gray-700">Mês</label>
                <select id="mes" name="mes" class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-[#EA792D] focus:ring-[#EA792D]">
                    @foreach ($meses as $numero => $nome)
                        <option value="{{ $numero }}" @selected((int) $mes === $numero)>{{ $nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="ano" class="mb-1.5 block text-sm font-medium text-gray-700">Ano</label>
                <input id="ano" type="number" name="ano" min="1900" max="2100" value="{{ $ano }}" class="w-full rounded-xl border-gray-300 text-sm shadow-sm focus:border-[#EA792D] focus:ring-[#EA792D]">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-[#EA792D] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#D56823]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="11" cy="11" r="7" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-width="2" d="m16 16 4 4"/>
                    </svg>
                    Aplicar filtros
                </button>
                <a href="{{ route('dashboard.produtos.mais-vendidos') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50" title="Limpar filtros">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M5.6 15a7 7 0 1 0-.6-5"/>
                    </svg>
                </a>
            </div>
        </div>
    </form>

    <div class="mb-7 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full bg-orange-50"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Unidades vendidas</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ number_format($totalUnidades, 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-gray-400">No período selecionado</p>
                </div>
                <div class="rounded-xl bg-orange-100 p-3 text-[#EA792D]">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 8-9-5-9 5m18 0-9 5m9-5v10l-9 5m0-10L3 8m9 5v10M3 8v10l9 5"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full bg-blue-50"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Códigos vendidos</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ number_format($totalCodigos, 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-gray-400">Códigos distintos</p>
                </div>
                <div class="rounded-xl bg-blue-100 p-3 text-blue-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 13V6a2 2 0 0 0-2-2h-7l-7 7 8 8 8-6ZM7 7h.01"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full bg-purple-50"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Famílias</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ number_format($rankingFamilias->count(), 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-gray-400">Com vendas registradas</p>
                </div>
                <div class="rounded-xl bg-purple-100 p-3 text-purple-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5h16v14H4zM4 10h16M9 5v14"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-full bg-emerald-50"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Categorias</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ number_format($rankingCategorias->count(), 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-gray-400">Com vendas registradas</p>
                </div>
                <div class="rounded-xl bg-emerald-100 p-3 text-emerald-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19V5m0 14h16M7 15l4-5 3 3 5-7"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4 flex items-center gap-3">
        <div class="h-8 w-1.5 rounded-full bg-[#EA792D]"></div>
        <div>
            <h2 class="text-lg font-bold text-gray-900">Análise visual das vendas</h2>
            <p class="text-sm text-gray-500">Três perspectivas diferentes do desempenho comercial.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-start justify-between border-b border-gray-100 p-5">
                <div>
                    <div class="mb-2 inline-flex rounded-lg bg-orange-100 px-2.5 py-1 text-xs font-semibold text-orange-700">RANKING 01</div>
                    <h3 class="text-lg font-bold text-gray-900">Códigos mais vendidos</h3>
                    <p class="mt-1 text-sm text-gray-500">Os 10 códigos com maior quantidade vendida.</p>
                </div>
                <div class="rounded-xl bg-orange-50 p-3 text-[#EA792D]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h6"/>
                    </svg>
                </div>
            </div>

            <div class="p-5">
                @if ($codigosGrafico->isNotEmpty())
                    <div class="mb-4 flex justify-between text-xs text-gray-400">
                        <span>Produto</span>
                        <span>Unidades vendidas</span>
                    </div>

                    <div class="space-y-5">
                        @foreach ($codigosGrafico as $i => $item)
                            @php
                                $valor = (float) $item->total_vendido;
                                $largura = ($valor / $maxCodigo) * 100;
                            @endphp
                            <div>
                                <div class="mb-2 flex items-center justify-between gap-3">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-xs font-bold {{ $i === 0 ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-500' }}">{{ $i + 1 }}</span>
                                        <span class="truncate text-sm font-semibold text-gray-700" title="{{ $item->det_cod }}">{{ $item->det_cod }}</span>
                                    </div>
                                    <span class="shrink-0 text-sm font-bold text-gray-900">{{ number_format($valor, 0, ',', '.') }}</span>
                                </div>
                                <div class="h-3 overflow-hidden rounded-full bg-gray-100">
                                    <div class="h-full rounded-full transition-all duration-500 {{ $i === 0 ? 'bg-[#EA792D]' : 'bg-orange-300' }}" style="width: {{ $largura }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex items-center gap-2 rounded-xl bg-orange-50 p-3 text-xs text-orange-800">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h6m0 0v6m0-6-8 8-4-4-4 4"/>
                        </svg>
                        As barras mostram a quantidade de cada código em relação ao líder do ranking.
                    </div>
                @else
                    <div class="py-12 text-center">
                        <p class="font-semibold text-gray-700">Nenhum produto encontrado</p>
                        <p class="mt-1 text-sm text-gray-500">Não há vendas registradas nesse período.</p>
                    </div>
                @endif
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-start justify-between border-b border-gray-100 p-5">
                <div>
                    <div class="mb-2 inline-flex rounded-lg bg-purple-100 px-2.5 py-1 text-xs font-semibold text-purple-700">RANKING 02</div>
                    <h3 class="text-lg font-bold text-gray-900">Participação por família</h3>
                    <p class="mt-1 text-sm text-gray-500">Como as famílias se distribuem no volume vendido.</p>
                </div>
                <div class="rounded-xl bg-purple-50 p-3 text-purple-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="9" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-width="2" d="M12 3v9h9"/>
                    </svg>
                </div>
            </div>

            <div class="p-5">
                @if ($totalFamiliasGrafico > 0)
                    <div class="flex flex-col items-center gap-6 sm:flex-row sm:items-center">
                        <div class="relative h-52 w-52 shrink-0">
                            <div class="h-full w-full rounded-full" style="background: {{ $fundoRosca }}"></div>
                            <div class="absolute inset-[24%] flex flex-col items-center justify-center rounded-full bg-white shadow-inner">
                                <span class="text-xs font-medium text-gray-400">Total exibido</span>
                                <span class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($totalRosca, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-400">unidades</span>
                            </div>
                        </div>

                        <div class="w-full min-w-0 space-y-4">
                            @foreach ($segmentosRosca as $segmento)
                                <div class="flex items-start gap-3">
                                    <span class="mt-1 h-3 w-3 shrink-0 rounded-full" style="background: {{ $segmento['cor'] }}"></span>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <span class="break-words text-sm font-medium text-gray-700">{{ $segmento['nome'] }}</span>
                                            <span class="shrink-0 text-sm font-bold text-gray-900">{{ number_format($segmento['percentual'], 1, ',', '.') }}%</span>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-400">{{ number_format($segmento['total'], 0, ',', '.') }} unidades</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 rounded-xl border border-purple-100 bg-purple-50/70 p-4">
                        <p class="text-sm font-semibold text-purple-900">Como interpretar</p>
                        <p class="mt-1 text-xs leading-relaxed text-purple-800">Cada fatia representa a participação de uma família no volume vendido. O gráfico destaca as cinco primeiras; as demais são reunidas em “Outras famílias”.</p>
                    </div>
                @else
                    <div class="py-12 text-center">
                        <p class="font-semibold text-gray-700">Nenhuma família encontrada</p>
                        <p class="mt-1 text-sm text-gray-500">Não há vendas registradas nesse período.</p>
                    </div>
                @endif
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm xl:col-span-2">
            <div class="flex flex-col gap-3 border-b border-gray-100 p-5 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <div class="mb-2 inline-flex rounded-lg bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">RANKING 03</div>
                    <h3 class="text-lg font-bold text-gray-900">Desempenho por categoria</h3>
                    <p class="mt-1 text-sm text-gray-500">Comparação visual das oito categorias com maior volume vendido.</p>
                </div>
                <div class="flex items-center gap-2 self-start rounded-xl bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700">
                    <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                    Unidades vendidas
                </div>
            </div>

            <div class="p-5">
                @if (count($pontosLinha) > 0)
                    <div class="overflow-x-auto">
                        <svg viewBox="0 0 700 280" class="h-auto min-w-[600px] w-full" role="img" aria-label="Gráfico de linha das quantidades vendidas por categoria">
                            @foreach ([0, 1, 2, 3, 4] as $linha)
                                @php
                                    $yGrade = $margemY + ($linha / 4) * $areaAltura;
                                    $valorGrade = $maxCategoria * (1 - $linha / 4);
                                @endphp
                                <line x1="{{ $margemX }}" y1="{{ $yGrade }}" x2="{{ $larguraGrafico - $margemX }}" y2="{{ $yGrade }}" stroke="#E5E7EB" stroke-width="1" stroke-dasharray="4 5"/>
                                <text x="25" y="{{ $yGrade + 4 }}" text-anchor="end" fill="#9CA3AF" font-size="10">{{ number_format($valorGrade, 0, ',', '.') }}</text>
                            @endforeach

                            @if (count($pontosLinha) > 1)
                                <polygon points="{{ $coordenadasArea }}" fill="#3B82F6" opacity="0.09"/>
                            @endif

                            @if (count($pontosLinha) > 1)
                                <polyline points="{{ $coordenadasLinha }}" fill="none" stroke="#3B82F6" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                            @endif

                            @foreach ($pontosLinha as $ponto)
                                <circle cx="{{ $ponto['x'] }}" cy="{{ $ponto['y'] }}" r="5.5" fill="#FFFFFF" stroke="#3B82F6" stroke-width="3"/>
                                <text x="{{ $ponto['x'] }}" y="{{ max($ponto['y'] - 12, 13) }}" text-anchor="middle" fill="#1D4ED8" font-size="11" font-weight="700">{{ number_format($ponto['total'], 0, ',', '.') }}</text>
                            @endforeach
                        </svg>
                    </div>

                    <div class="mt-2 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($pontosLinha as $i => $ponto)
                            <div class="flex min-w-0 items-start gap-3 rounded-xl border border-gray-100 bg-gray-50/70 p-3">
                                <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-xs font-bold text-blue-700">{{ $i + 1 }}</span>
                                <div class="min-w-0 flex-1">
                                    <p class="break-words text-sm font-medium text-gray-600">{{ $ponto['nome'] }}</p>
                                    <p class="mt-1 text-lg font-bold text-gray-900">{{ number_format($ponto['total'], 0, ',', '.') }} <span class="text-xs font-normal text-gray-400">un.</span></p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 flex items-start gap-3 rounded-xl border border-blue-100 bg-blue-50 p-4">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20Z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-blue-900">Leitura do gráfico</p>
                            <p class="mt-1 text-xs leading-relaxed text-blue-800">Os pontos representam a quantidade vendida em cada categoria, organizadas da maior para a menor. Como as categorias não representam uma sequência temporal, a linha serve para comparar os valores, não para indicar uma evolução ao longo do tempo.</p>
                        </div>
                    </div>
                @else
                    <div class="py-12 text-center">
                        <p class="font-semibold text-gray-700">Nenhuma categoria encontrada</p>
                        <p class="mt-1 text-sm text-gray-500">Não há vendas registradas nesse período.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <div class="mt-6 flex flex-col gap-2 rounded-2xl border border-gray-200 bg-white p-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold text-gray-800">Critério dos dados</p>
            <p class="mt-1 text-xs leading-relaxed text-gray-500">Considera orçamentos aprovados ou finalizados, filtrados pela data de início do orçamento. As quantidades são somadas separadamente por código, família e categoria.</p>
        </div>
        <div class="inline-flex shrink-0 items-center gap-2 self-start rounded-lg bg-gray-100 px-3 py-2 text-xs font-medium text-gray-600 sm:self-center">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            Dados do período
        </div>
    </div>
</div>
@endsection