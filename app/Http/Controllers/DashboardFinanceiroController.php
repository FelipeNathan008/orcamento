<?php

namespace App\Http\Controllers;

use App\Models\DetalhesFormaPag;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardFinanceiroController extends Controller
{
    public function parcelas(Request $request)
    {
        $hoje = now()->startOfDay();

        $query = DetalhesFormaPag::with([
            'formaPagamento.financeiro',
            'formaPagamento.tipoPagamento'
        ])->whereIn('det_situacao', [
            'Não Pago',
            'Inadimplencia',
            'Atrasado'
        ]);

        if ($request->filtro === 'pagar') {
            $query->whereMonth('det_forma_data_venc', $request->mes ?? $hoje->month)
                ->whereYear('det_forma_data_venc', $request->ano ?? $hoje->year)
                ->where('det_situacao', 'Não Pago');
        } else {
            $query->whereDate('det_forma_data_venc', '<', $hoje);

            if ($request->filtro === 'atrasadas') {
                $query->whereIn('det_situacao', [
                    'Inadimplencia',
                    'Atrasado'
                ]);
            }
        }

        if ($request->filled('situacao')) {
            $query->where('det_situacao', $request->situacao);
        }

        if ($request->filled('id_det_forma')) {
            $query->where('id_det_forma', $request->id_det_forma);
        }

        if ($request->filled('id_fin')) {
            $query->whereHas('formaPagamento', function ($q) use ($request) {
                $q->where('financeiro_id_fin', $request->id_fin);
            });
        }

        if ($request->filled('id_orcamento')) {
            $query->whereHas('formaPagamento.financeiro', function ($q) use ($request) {
                $q->where('orcamento_id_orcamento', $request->id_orcamento);
            });
        }

        if ($request->filled('data_vencimento')) {
            $query->whereDate('det_forma_data_venc', $request->data_vencimento);
        }

        $parcelas = $query
            ->orderBy('det_forma_data_venc')
            ->orderBy('id_det_forma')
            ->paginate(10)
            ->withQueryString();

        return view('view_dashboard.parcelas_financeiro', compact('parcelas'));
    }

    public function mapaFinanceiro(Request $request)
    {
        $mes = (int) ($request->mes ?? now()->month);
        $ano = (int) ($request->ano ?? now()->year);

        $dataMes = Carbon::create($ano, $mes, 1);
        $inicioMes = $dataMes->copy()->startOfMonth();
        $fimMes = $dataMes->copy()->endOfMonth();

        $parcelas = DetalhesFormaPag::with([
            'formaPagamento.financeiro',
            'formaPagamento.tipoPagamento'
        ])->where('det_situacao', 'Não Pago')
            ->whereBetween('det_forma_data_venc', [
                $inicioMes->startOfDay(),
                $fimMes->endOfDay()
            ])->orderBy('det_forma_data_venc')
            ->orderBy('id_det_forma')
            ->get();

        $parcelasPorDia = $parcelas->groupBy(function ($parcela) {
            return Carbon::parse($parcela->det_forma_data_venc)->day;
        });

        $dias = collect();

        for ($dia = 1; $dia <= $dataMes->daysInMonth; $dia++) {
            $parcelasDia = $parcelasPorDia->get($dia, collect());

            if ($parcelasDia->isEmpty()) {
                continue;
            }

            $dias->push([
                'dia' => $dia,
                'data' => $dataMes->copy()->day($dia),
                'parcelas' => $parcelasDia,
                'quantidade' => $parcelasDia->count(),
                'valor' => $parcelasDia->sum('det_forma_valor_parcela'),
            ]);
        }

        $totalParcelas = $parcelas->count();
        $valorTotal = $parcelas->sum('det_forma_valor_parcela');

        $meses = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        ];

        $nomeMes = $meses[$mes];

        return view('view_dashboard.mapa_financeiro', compact(
            'dias',
            'dataMes',
            'totalParcelas',
            'valorTotal',
            'nomeMes'
        ));
    }

    public function parcelasDia(Request $request)
    {
        $dataString = $request->input('data', now()->format('Y-m-d'));

        $request->merge(['data' => $dataString]);

        $request->validate([
            'data' => ['required', 'date_format:Y-m-d'],
            'id_det_forma' => ['nullable', 'integer'],
            'id_fin' => ['nullable', 'integer'],
            'id_orcamento' => ['nullable', 'integer'],
        ]);

        $data = Carbon::createFromFormat('Y-m-d', $dataString)->startOfDay();

        $query = DetalhesFormaPag::with([
            'formaPagamento.financeiro',
            'formaPagamento.tipoPagamento'
        ])->where('det_situacao', 'Não Pago')
            ->whereDate('det_forma_data_venc', $data);

        if ($request->filled('id_det_forma')) {
            $query->where('id_det_forma', $request->id_det_forma);
        }

        if ($request->filled('id_fin')) {
            $query->whereHas('formaPagamento', function ($q) use ($request) {
                $q->where('financeiro_id_fin', $request->id_fin);
            });
        }

        if ($request->filled('id_orcamento')) {
            $query->whereHas('formaPagamento.financeiro', function ($q) use ($request) {
                $q->where('orcamento_id_orcamento', $request->id_orcamento);
            });
        }

        $parcelas = $query
            ->orderBy('id_det_forma')
            ->paginate(10)
            ->withQueryString();

        return view('view_dashboard.parcelas_financeiro_dia', compact(
            'parcelas',
            'data'
        ));
    }
}
