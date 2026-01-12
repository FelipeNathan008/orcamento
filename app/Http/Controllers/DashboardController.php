<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $familiaMaisVendida = DB::table('orcamento as o')
            ->join(
                'detalhes_orcamento as d',
                'd.orcamento_id_orcamento',
                '=',
                'o.id_orcamento'
            )
            ->join(
                'produto as p',
                'p.prod_cod',
                '=',
                'd.det_cod'
            )
            ->whereMonth('o.created_at', now()->month)
            ->whereYear('o.created_at', now()->year)
            ->select(
                'p.prod_familia',
                DB::raw('SUM(d.det_quantidade) as total_vendido')
            )
            ->groupBy('p.prod_familia')
            ->orderByDesc('total_vendido')
            ->limit(1)
            ->first();


        $orcamentosMes = DB::table('orcamento')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $orcamentoParaAprovacao = DB::table('orcamento')
            ->where('orc_status', 'para aprovacao')
            ->count();

        $orcamentoAprovado = DB::table('orcamento')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('orc_status', 'aprovado')
            ->count();

        $orcamentoFinalizado = DB::table('orcamento')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('orc_status', 'finalizado')
            ->count();

        $orcamentoRejeitado = DB::table('orcamento')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('orc_status', 'rejeitado')
            ->count();

        $orcamentoAtrasado = DB::table('orcamento')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('orc_status', 'rejeitado')
            ->count();

        $totalMes = 0;
        $orcamentos = Orcamento::with('detalhesOrcamento.customizacoes')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        foreach ($orcamentos as $orcamento) {
            foreach ($orcamento->detalhesOrcamento as $detalhe) {

                // subtotal do item
                $subtotalDetalhe =
                    $detalhe->det_quantidade * $detalhe->det_valor_unit;

                // soma das customizações
                foreach ($detalhe->customizacoes as $customizacao) {
                    $subtotalDetalhe += $customizacao->cust_valor;
                }

                // soma no total geral do mês
                $totalMes += $subtotalDetalhe;
            }
        }

        $mesAtual = now()->month;
        $anoAtual = now()->year;

        $financeiroPagar = DB::table('detalhes_forma_pag')
            ->where('det_situacao', 'Não pago')
            ->whereMonth('det_forma_data_venc', $mesAtual)
            ->whereYear('det_forma_data_venc', $anoAtual)
            ->count();

        $financeiroAtraso = DB::table('detalhes_forma_pag')
            ->where('det_situacao', 'Não pago')
            ->whereMonth('det_forma_data_venc', $mesAtual)
            ->whereYear('det_forma_data_venc', $anoAtual)
            ->count();

        $financeiroPago = DB::table('detalhes_forma_pag')
            ->where('det_situacao', 'Pago')
            ->whereMonth('det_forma_data_venc', $mesAtual)
            ->whereYear('det_forma_data_venc', $anoAtual)
            ->count();

        $financeiroQuitado = DB::table('detalhes_forma_pag')
            ->where('det_situacao', 'Quitado')
            ->whereMonth('det_forma_data_venc', $mesAtual)
            ->whereYear('det_forma_data_venc', $anoAtual)
            ->count();

        $hoje = now()->format('Y-m-d'); // formato yyyy-mm-dd

        $previsaoDoDia = DB::table('detalhes_forma_pag')
            ->whereDate('det_forma_data_venc', $hoje)
            ->sum('det_forma_valor_parcela');

        // Total do dia pagos ou quitados
        $previsaoDoDiaPago = DB::table('detalhes_forma_pag')
            ->whereIn('det_situacao', ['Pago', 'Quitado'])
            ->whereDate('det_forma_data_venc', $hoje)
            ->sum('det_forma_valor_parcela');

        // Total do dia não pagos
        $previsaoDoDiaNaoPago = DB::table('detalhes_forma_pag')
            ->where('det_situacao', 'Não pago') // aqui pode usar where normal
            ->whereDate('det_forma_data_venc', $hoje)
            ->sum('det_forma_valor_parcela');

        return view('dashboard', compact(
            'familiaMaisVendida',
            'orcamentosMes',
            'orcamentoParaAprovacao',
            'orcamentoAprovado',
            'orcamentoFinalizado',
            'orcamentoRejeitado',
            'orcamentoAtrasado',
            'totalMes',
            'financeiroPagar',
            'financeiroAtraso',
            'financeiroPago',
            'financeiroQuitado',
            'previsaoDoDia',
            'previsaoDoDiaPago',
            'previsaoDoDiaNaoPago'
        ));
    }
}
