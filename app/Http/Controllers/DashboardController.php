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
            ->whereMonth('o.orc_data_inicio', now()->month)
            ->whereYear('o.orc_data_inicio', now()->year)
            ->select(
                'p.prod_familia',
                DB::raw('SUM(d.det_quantidade) as total_vendido')
            )
            ->groupBy('p.prod_familia')
            ->orderByDesc('total_vendido')
            ->limit(1)
            ->first();


        $orcamentosMes = DB::table('orcamento')
            ->whereMonth('orc_data_inicio', now()->month)
            ->whereYear('orc_data_inicio', now()->year)
            ->count();

        $orcamentoParaAprovacao = DB::table('orcamento')
            ->where('orc_status', 'para aprovacao')
            ->count();

        $orcamentoAprovado = DB::table('orcamento')
            ->whereMonth('orc_data_inicio', now()->month)
            ->whereYear('orc_data_inicio', now()->year)
            ->where('orc_status', 'aprovado')
            ->count();

        $orcamentoFinalizado = DB::table('orcamento')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->where('orc_status', 'finalizado')
            ->count();

        $orcamentoRejeitado = DB::table('orcamento')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->where('orc_status', 'rejeitado')
            ->count();

        $orcamentoAtrasado = DB::table('orcamento') //pega qualquer pendente vencido independente da data de incio 
            ->where('orc_status', 'pendente')
            ->where('orc_data_fim', '<', now()->startOfDay())
            ->count();


        $orcamentoPendente = DB::table('orcamento')
            ->where('orc_status', 'pendente')
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

        //Fluxo dos Status
        $statusUm = DB::table('financeiro')
            ->where('fin_status', 'Aguardando pagamento')
            ->count();

        $statusDois = DB::table('financeiro')
            ->where('fin_status', 'Pagamento realizado')
            ->count();

        $statusTres = DB::table('financeiro')
            ->where('fin_status', 'Análise pedido')
            ->count();

        $statusQuatro = DB::table('financeiro')
            ->where('fin_status', 'Pedido fábrica')
            ->count();

        $statusCinco = DB::table('financeiro')
            ->where('fin_status', 'Transportadora')
            ->count();

        $statusSeis = DB::table('financeiro')
            ->where('fin_status', 'Entregue')
            ->count();



        //Financeiro
        $mesAtual = now()->month;
        $anoAtual = now()->year;

        $financeiroPagar = DB::table('detalhes_forma_pag')
            ->where('det_situacao', 'Não pago')
            ->whereMonth('det_forma_data_venc', $mesAtual)
            ->whereYear('det_forma_data_venc', $anoAtual)
            ->count();

        $financeiroAtraso = DB::table('detalhes_forma_pag')
            ->whereIn('det_situacao', ['Não pago', 'Inadimplencia'])
            ->where('det_forma_data_venc', '<', now()->startOfDay())
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
            ->whereIn('det_situacao', ['Não pago', 'Inadimplencia', 'Acordo'])
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

        $valorAtrasoNaoPago = DB::table('detalhes_forma_pag')
            ->whereIn('det_situacao', ['Não pago', 'Inadimplencia'])
            ->where('det_forma_data_venc', '<', now()->startOfDay())
            ->sum('det_forma_valor_parcela');

        $valorTotalMes = DB::table('detalhes_forma_pag')
            ->whereIn('det_situacao', ['Pago', 'Quitado'])
            ->whereMonth('det_forma_data_venc', $mesAtual)
            ->whereYear('det_forma_data_venc', $anoAtual)
            ->sum('det_forma_valor_parcela');

        return view('dashboard', compact(
            'familiaMaisVendida',
            'orcamentosMes',
            'orcamentoParaAprovacao',
            'orcamentoAprovado',
            'orcamentoFinalizado',
            'orcamentoRejeitado',
            'orcamentoAtrasado',
            'orcamentoPendente',
            'totalMes',
            'statusUm',
            'statusDois',
            'statusTres',
            'statusQuatro',
            'statusCinco',
            'statusSeis',
            'financeiroPagar',
            'financeiroAtraso',
            'financeiroPago',
            'financeiroQuitado',
            'previsaoDoDia',
            'previsaoDoDiaPago',
            'previsaoDoDiaNaoPago',
            'valorAtrasoNaoPago',
            'valorTotalMes'
        ));
    }
}
