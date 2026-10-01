<?php

namespace App\Traits;

use App\Models\Orcamento;
use App\Models\Financeiro;
use App\Models\LogStatus;
use App\Models\StatusMercadoria;

trait SincronizaStatusFinanceiro
{
    protected function sincronizarStatusFinanceiro(int $orcamentoId): void
    {
        $orcamento = Orcamento::with('fracionados')->find($orcamentoId);

        if (!$orcamento || $orcamento->fracionados->isEmpty()) {
            return;
        }

        // só sincroniza quando o fracionamento bate 100% com o orçamento
        $valorBrutoOriginal = (float) ($orcamento->total_bruto ?? 0);
        $valorFracionado = $orcamento->fracionados->sum(
            fn($f) => (float) ($f->total_bruto ?? 0)
        );

        if (abs($valorBrutoOriginal - $valorFracionado) >= 0.01) {
            return;
        }

        $ordemFracionado = [
            'pendente' => 0,
            'pedido fabrica' => 1,
            'transportadora' => 2,
            'entregue' => 3,
        ];

        // nível do fracionado mais atrasado (o "gargalo")
        $nivelMaisBaixo = $orcamento->fracionados->min(function ($f) use ($ordemFracionado) {
            $status = strtolower(trim($f->orc_status));
            return $ordemFracionado[$status] ?? 0;
        });

        $statusMaisAtrasado = array_search($nivelMaisBaixo, $ordemFracionado, true);

        // mapeia o status do fracionado para o id do status_mercadoria equivalente
        $mapaStatusMercadoria = [
            'pedido fabrica' => 4,
            'transportadora' => 5,
            'entregue' => 6,
        ];

        // enquanto existir fracionado 'pendente', não há status_mercadoria
        // equivalente -> não força avanço do financeiro
        if (!isset($mapaStatusMercadoria[$statusMaisAtrasado])) {
            return;
        }

        $idStatusAlvo = $mapaStatusMercadoria[$statusMaisAtrasado];

        $financeiro = Financeiro::where(
            'orcamento_id_orcamento',
            $orcamentoId
        )->first();

        if (!$financeiro) {
            return;
        }

        // avança passo a passo, na ordem real do status_mercadoria,
        // sem pular e sem retroceder, até (no máximo) o status alvo
        $logs = LogStatus::where('log_id_orcamento', $orcamentoId)
            ->orderBy('status_mercadoria_id_status')
            ->get();

        foreach ($logs as $log) {

            if ($log->status_mercadoria_id_status > $idStatusAlvo) {
                break; // não avança além do que o fracionamento permite
            }

            if ($log->log_situacao == 1) {
                continue; // já concluído, nunca retrocede
            }

            $log->update(['log_situacao' => 1]);

            $statusNome = StatusMercadoria::where(
                'id_status_merc',
                $log->status_mercadoria_id_status
            )->value('status_merc_nome');

            $financeiro->update(['fin_status' => $statusNome]);
        }
    }
}
