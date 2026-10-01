<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\AtualizarStatusParcelas;
use Illuminate\Http\Request;
use App\Models\OrcamentoFracionado;

class DashboardController extends Controller
{
    public function index(AtualizarStatusParcelas $atualizarStatusParcelas)
    {
        $atualizarStatusParcelas->executar();

        $familiaMaisVendida = DB::table('orcamento as o')
            ->join('detalhes_orcamento as d', 'd.orcamento_id_orcamento', '=', 'o.id_orcamento')
            ->whereMonth('o.orc_data_inicio', now()->month)
            ->whereYear('o.orc_data_inicio', now()->year)
            ->whereIn('o.orc_status', ['aprovado', 'finalizado'])
            ->whereNotNull('d.det_familia')
            ->where('d.det_familia', '!=', '')
            ->select(
                'd.det_familia',
                DB::raw('SUM(d.det_quantidade) as total_vendido')
            )
            ->groupBy('d.det_familia')
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

        $orcamentoAtrasado = DB::table('orcamento')
            ->where('orc_status', 'pendente')
            ->where('orc_data_fim', '<', now()->startOfDay())
            ->count();


        $orcamentoPendente = DB::table('orcamento')
            ->where('orc_status', 'pendente')
            ->count();

        // Orçamentos Fracionados
        $fracionadosMes = DB::table('orcamento_fracionado')
            ->count();

        $fracionadosPendente = DB::table('orcamento_fracionado')
            ->where('orc_status', 'pendente')
            ->count();

        $fracionadosPedidoFabrica = DB::table('orcamento_fracionado')
            ->where('orc_status', 'pedido fabrica')
            ->count();

        $fracionadosTransportadora = DB::table('orcamento_fracionado')
            ->where('orc_status', 'transportadora')
            ->count();

        $fracionadosEntregue = DB::table('orcamento_fracionado')
            ->where('orc_status', 'entregue')
            ->count();

        $totalMes = 0;
        $orcamentos = Orcamento::with('detalhesOrcamento.customizacoes')
            ->whereMonth('orc_data_inicio', now()->month)
            ->whereYear('orc_data_inicio', now()->year)
            ->whereIn('orc_status', ['aprovado', 'finalizado'])
            ->get();

        foreach ($orcamentos as $orcamento) {
            $totalBruto = 0;
            foreach ($orcamento->detalhesOrcamento as $detalhe) {

                $quantidade = (int) ($detalhe->det_quantidade ?? 0);
                // Produto
                $totalBruto +=
                    $quantidade * (float) ($detalhe->det_valor_unit ?? 0);
                // Customizações
                foreach ($detalhe->customizacoes as $customizacao) {

                    $totalBruto +=
                        $quantidade * (float) ($customizacao->cust_valor ?? 0);
                }
            }

            // Desconto
            $valorDesconto = 0;

            if ($orcamento->orc_desconto_tipo === 'percentual') {

                $valorDesconto =
                    $totalBruto *
                    ((float) ($orcamento->orc_desconto_valor ?? 0) / 100);
            } elseif ($orcamento->orc_desconto_tipo === 'valor') {

                $valorDesconto =
                    (float) ($orcamento->orc_desconto_valor ?? 0);
            }

            // Impede desconto maior que o total
            $valorDesconto = min(
                $valorDesconto,
                $totalBruto
            );

            // Total final do orçamento
            $totalComDesconto =
                $totalBruto - $valorDesconto;

            $totalMes += $totalComDesconto;
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
            ->whereDate('det_forma_data_venc', '>=', now()->startOfDay())
            ->count();

        $financeiroAtraso = DB::table('detalhes_forma_pag')
            ->whereIn('det_situacao', ['Não pago', 'Inadimplencia', 'Atrasado'])
            ->where('det_forma_data_venc', '<', now()->startOfDay())
            ->count();

        $valorAtrasoNaoPago = DB::table('detalhes_forma_pag')
            ->whereIn('det_situacao', ['Não pago', 'Inadimplencia', 'Atrasado'])
            ->where('det_forma_data_venc', '<', now()->startOfDay())
            ->sum('det_forma_valor_parcela');

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

        $valorTotalMes = DB::table('detalhes_forma_pag')
            ->whereIn('det_situacao', ['Pago', 'Quitado'])
            ->whereMonth('det_forma_data_venc', $mesAtual)
            ->whereYear('det_forma_data_venc', $anoAtual)
            ->sum('det_forma_valor_parcela');

        return view('view_dashboard.dashboard', compact(
            'familiaMaisVendida',
            'orcamentosMes',
            'orcamentoParaAprovacao',
            'orcamentoAprovado',
            'orcamentoFinalizado',
            'orcamentoRejeitado',
            'orcamentoAtrasado',
            'orcamentoPendente',
            'fracionadosMes',
            'fracionadosPendente',
            'fracionadosPedidoFabrica',
            'fracionadosTransportadora',
            'fracionadosEntregue',
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

    public function orcamentos(Request $request)
    {
        $today = Carbon::now()->startOfDay();

        $query = Orcamento::with([
            'clienteOrcamento',
            'detalhesOrcamento.customizacoes'
        ]);

        // ID do orçamento
        if ($request->filled('id_orcamento')) {
            $query->where(
                'id_orcamento',
                $request->id_orcamento
            );
        }

        // Código da fábrica
        if ($request->filled('orc_cod_fabrica')) {
            $query->where(
                'orc_cod_fabrica',
                'like',
                '%' . trim($request->orc_cod_fabrica) . '%'
            );
        }

        // Código interno
        if ($request->filled('orc_cod_interno')) {
            $query->where(
                'orc_cod_interno',
                'like',
                '%' . trim($request->orc_cod_interno) . '%'
            );
        }

        // Data início
        if ($request->filled('data_inicio')) {
            $query->whereDate(
                'orc_data_inicio',
                $request->data_inicio
            );
        }

        // Data fim
        if ($request->filled('data_fim')) {
            $query->whereDate(
                'orc_data_fim',
                $request->data_fim
            );
        }

        // Mês da data de início
        if ($request->filled('mes')) {
            $query->whereMonth('orc_data_inicio', $request->mes);
        }

        // Ano da data de início
        if ($request->filled('ano')) {
            $query->whereYear('orc_data_inicio', $request->ano);
        }

        // Status
        if ($request->filled('status_query')) {
            $query->where(
                'orc_status',
                $request->status_query
            );
        }

        // Vencimento
        $filtroVencimento = $request->filtro_vencimento ?? 'todos';

        if ($filtroVencimento === 'ativos') {

            $query->where('orc_status', '!=', 'rejeitado')
                ->where(function ($q) use ($today) {
                    $q->whereIn('orc_status', ['aprovado', 'finalizado'])
                        ->orWhere('orc_data_fim', '>=', $today);
                });
        } elseif ($filtroVencimento === 'vencidos') {

            $query->where(function ($q) use ($today) {

                $q->where(function ($sub) use ($today) {

                    $sub->whereIn('orc_status', [
                        'pendente',
                        'para aprovacao'
                    ])
                        ->where('orc_data_fim', '<', $today);
                })->orWhere('orc_status', 'rejeitado');
            });
        }

        $orcamentos = $query
            ->orderByDesc('id_orcamento')
            ->paginate(10)
            ->withQueryString();

        return view(
            'view_dashboard.orcamentos',
            compact('orcamentos')
        );
    }

    public function orcamentosFracionados(Request $request)
    {
        $query = OrcamentoFracionado::with([
            'clienteOrcamento',
            'detalhesOrcamentoFracionado.customizacoes'
        ]);

        // ID do orçamento principal
        if ($request->filled('orcamento_id_orcamento')) {
            $query->where(
                'orcamento_id_orcamento',
                $request->orcamento_id_orcamento
            );
        }

        // ID do orçamento fracionado
        if ($request->filled('id_orcamento_fracionado')) {
            $query->where(
                'id_orcamento_fracionado',
                $request->id_orcamento_fracionado
            );
        }

        // Código da fábrica
        if ($request->filled('orc_cod_fabrica')) {
            $query->where(
                'orc_cod_fabrica',
                'like',
                '%' . trim($request->orc_cod_fabrica) . '%'
            );
        }

        // Código interno
        if ($request->filled('orc_cod_interno')) {
            $query->where(
                'orc_cod_interno',
                'like',
                '%' . trim($request->orc_cod_interno) . '%'
            );
        }

        // Status
        if ($request->filled('status_query')) {
            $query->where(
                'orc_status',
                $request->status_query
            );
        }

        $orcamentos = $query
            ->orderByDesc('id_orcamento_fracionado')
            ->paginate(10)
            ->withQueryString();

        return view(
            'view_dashboard.orcamentos_fracionados',
            compact('orcamentos')
        );
    }
}
