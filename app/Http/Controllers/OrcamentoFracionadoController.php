<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\OrcamentoFracionado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrcamentoFracionadoController extends Controller
{
    public function index($id)
    {
        $orcamento = Orcamento::with([
            'clienteOrcamento',
            'detalhesOrcamento.customizacoes',
            'fracionados'
        ])
            ->findOrFail($id);

        return view('view_orcamento_fracionado.index', compact('orcamento'));
    }

    public function store($orcamento)
    {
        $orcamentoId = $orcamento;

        DB::transaction(function () use ($orcamentoId) {

            $orcamento = Orcamento::findOrFail($orcamentoId);


            $numeroFracao = OrcamentoFracionado::where(
                'orcamento_id_orcamento',
                $orcamento->id_orcamento
            )->count();

            OrcamentoFracionado::create([
                'orcamento_id_orcamento' => $orcamento->id_orcamento,
                'orc_fracao' => $numeroFracao + 1,
                'cliente_orcamento_id_co' => $orcamento->cliente_orcamento_id_co,
                'orc_data_inicio' => $orcamento->orc_data_inicio,
                'orc_data_fim' => $orcamento->orc_data_fim,
                'orc_status' => $orcamento->orc_status,
                'orc_anotacao_espec' => $orcamento->orc_anotacao_espec,
                'orc_anotacao_geral' => $orcamento->orc_anotacao_geral,
                'orc_cod_fabrica' => $orcamento->orc_cod_fabrica,
                'orc_cod_interno' => $orcamento->orc_cod_interno,
                'orc_desconto_tipo' => $orcamento->orc_desconto_tipo,
                'orc_desconto_valor' => 0,
                'orc_desconto_motivo' => null,
            ]);
        });

        return redirect()
            ->route('orcamento.fracionado.index', $orcamentoId)
            ->with('success', 'Orçamento fracionado criado com sucesso!');
    }

    public function visualizar($id)
    {
        $orcamento = OrcamentoFracionado::with([
            'clienteOrcamento',
            'detalhesOrcamentoFracionado.customizacoes'
        ])
            ->findOrFail($id);

        $clienteOrcamento = $orcamento->clienteOrcamento;

        return view(
            'view_orcamento_fracionado.visualizar',
            compact(
                'orcamento',
                'clienteOrcamento'
            )
        );
    }

    public function destroy($id)
    {
        $orcamentoFracionado = OrcamentoFracionado::findOrFail($id);
        DB::transaction(function () use ($orcamentoFracionado) {
            foreach ($orcamentoFracionado->detalhesOrcamentoFracionado as $detalhe) {
                if (method_exists($detalhe, 'customizacoes')) {
                    $detalhe->customizacoes()->delete();
                }
                $detalhe->delete();
            }

            $orcamentoFracionado->delete();
        });

        return redirect()
            ->back()
            ->with('success', 'Orçamento fracionado excluído com sucesso!');
    }
}
