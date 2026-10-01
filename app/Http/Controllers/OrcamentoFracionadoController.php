<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\OrcamentoFracionado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use App\Models\ContaBancaria;
use App\Models\Movimentacao;
use App\Models\TipoFluxoCaixa;
use App\Models\Financeiro;
use App\Models\LogStatus;
use App\Models\StatusMercadoria;
use App\Traits\SincronizaStatusFinanceiro;
use Exception;

class OrcamentoFracionadoController extends Controller
{
    use SincronizaStatusFinanceiro;
    public function index($id)
    {
        $orcamento = Orcamento::with([
            'clienteOrcamento',
            'detalhesOrcamento.customizacoes',
            'fracionados' => function ($query) {
                $query->with([
                    'detalhesOrcamentoFracionado.customizacoes'
                ])->withCount('detalhesOrcamentoFracionado');
            }
        ])->findOrFail($id);

        $contas = ContaBancaria::all();

        $tipos = TipoFluxoCaixa::all();

        $movimentacoes = Movimentacao::all();

        $tipoDespesaUP = TipoFluxoCaixa::where(
            'tipo_flu_nome',
            'Despesa UP'
        )->first();

        $movSaida = Movimentacao::where(
            'mov_nome',
            'Saída'
        )->first();

        return view(
            'view_orcamento_fracionado.index',
            compact(
                'orcamento',
                'tipoDespesaUP',
                'movSaida',
                'tipos',
                'movimentacoes',
                'contas'
            )
        );
    }

    public function store($orcamento)
    {
        $orcamentoId = $orcamento;

        try {
            DB::transaction(function () use ($orcamentoId) {

                $orcamento = Orcamento::with('fracionados')
                    ->findOrFail($orcamentoId);

                $valorBrutoOriginal = (float) ($orcamento->total_bruto ?? 0);

                $valorFracionado = $orcamento->fracionados->sum(function ($fracionado) {
                    return (float) ($fracionado->total_bruto ?? 0);
                });

                $diferenca = $valorBrutoOriginal - $valorFracionado;

                if (abs($diferenca) < 0.01) {
                    throw ValidationException::withMessages([
                        'orcamento' =>
                        'Não é possível criar um novo orçamento fracionado, pois todo o valor do orçamento principal já foi fracionado.'
                    ]);
                }

                $numeroFracao = OrcamentoFracionado::where(
                    'orcamento_id_orcamento',
                    $orcamento->id_orcamento
                )->max('orc_fracao') ?? 0;

                OrcamentoFracionado::create([
                    'orcamento_id_orcamento' => $orcamento->id_orcamento,
                    'orc_fracao' => $numeroFracao + 1,
                    'cliente_orcamento_id_co' => $orcamento->cliente_orcamento_id_co,
                    'orc_status' => 'pendente',
                    'orc_anotacao_espec' => $orcamento->orc_anotacao_espec,
                    'orc_anotacao_geral' => $orcamento->orc_anotacao_geral,
                    'orc_cod_fabrica' => null,
                    'orc_cod_interno' => null,
                ]);
            });

            return redirect()
                ->route('orcamento.fracionado.index', $orcamentoId)
                ->with(
                    'success',
                    'Orçamento fracionado criado com sucesso!'
                );
        } catch (ValidationException $e) {

            return back()
                ->withErrors($e->errors());
        } catch (Exception $e) {

            return back()
                ->with(
                    'error',
                    'Não foi possível criar o orçamento fracionado: ' .
                        $e->getMessage()
                );
        }
    }

    public function edit($id)
    {
        $orcamentoFracionado = OrcamentoFracionado::with([
            'clienteOrcamento',
            'orcamento'
        ])->findOrFail($id);

        $orcamento = $orcamentoFracionado->orcamento;
        $clienteSelecionado = $orcamentoFracionado->clienteOrcamento;

        return view(
            'view_orcamento_fracionado.edit',
            compact(
                'orcamentoFracionado',
                'orcamento',
                'clienteSelecionado'
            )
        );
    }

    public function update(Request $request, $id)
    {
        try {
            $orcamento = OrcamentoFracionado::findOrFail($id);

            $regrasCodigoFabrica = [
                'string',
                'max:60',

                Rule::unique(
                    'orcamento_fracionado',
                    'orc_cod_fabrica'
                )->ignore(
                    $orcamento->id_orcamento_fracionado,
                    'id_orcamento_fracionado'
                ),

                Rule::unique(
                    'orcamento',
                    'orc_cod_fabrica'
                ),
            ];

            if ($orcamento->orc_status === 'pendente') {
                array_unshift($regrasCodigoFabrica, 'nullable');
            } else {
                array_unshift($regrasCodigoFabrica, 'required');
            }

            $validatedData = $request->validate([
                'orc_cod_fabrica' => $regrasCodigoFabrica,

                'orc_cod_interno' => [
                    'nullable',
                    'string',
                    'max:60',

                    Rule::unique(
                        'orcamento_fracionado',
                        'orc_cod_interno'
                    )->ignore(
                        $orcamento->id_orcamento_fracionado,
                        'id_orcamento_fracionado'
                    ),

                    Rule::unique(
                        'orcamento',
                        'orc_cod_interno'
                    ),
                ],

                'orc_anotacao_geral' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ], [
                'orc_cod_fabrica.required' =>
                'O código de fábrica é obrigatório quando o status não está pendente.',

                'orc_cod_fabrica.unique' =>
                'Este código de fábrica já está cadastrado em um orçamento.',

                'orc_cod_fabrica.max' =>
                'O código de fábrica não pode ter mais de 60 caracteres.',

                'orc_cod_interno.unique' =>
                'Este código interno já está cadastrado em um orçamento.',

                'orc_cod_interno.max' =>
                'O código interno não pode ter mais de 60 caracteres.',
            ]);

            $orcamento->update([
                'orc_cod_fabrica' => $validatedData['orc_cod_fabrica'] ?? null,
                'orc_cod_interno' => $validatedData['orc_cod_interno'] ?? null,
                'orc_anotacao_geral' => $validatedData['orc_anotacao_geral'] ?? null,
            ]);

            return redirect()
                ->route(
                    'orcamento.fracionado.index',
                    $orcamento->orcamento_id_orcamento
                )
                ->with(
                    'success',
                    'Orçamento fracionado atualizado com sucesso!'
                );
        } catch (ValidationException $e) {

            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {

            return back()
                ->with(
                    'error',
                    'Não foi possível atualizar o orçamento fracionado: ' .
                        $e->getMessage()
                )
                ->withInput();
        }
    }

    public function visualizar($id)
    {
        $orcamento = OrcamentoFracionado::with([
            'clienteOrcamento',
            'detalhesOrcamentoFracionado.customizacoes'
        ])->findOrFail($id);

        $clienteOrcamento = $orcamento->clienteOrcamento;

        return view(
            'view_orcamento_fracionado.visualizar',
            compact(
                'orcamento',
                'clienteOrcamento'
            )
        );
    }

    public function prosseguir($id)
    {
        $fracionado = OrcamentoFracionado::findOrFail($id);

        $valorAtual = (float) ($fracionado->total_bruto ?? 0);

        if ($valorAtual <= 0) {
            return back()->with(
                'error',
                'Não é possível prosseguir com o status. O valor total atual do orçamento fracionado deve ser maior que R$ 0,00.'
            );
        }

        $proximosStatus = [
            'pendente' => 'pedido fabrica',
            'pedido fabrica' => 'transportadora',
            'transportadora' => 'entregue',
        ];

        $statusAtual = strtolower(trim($fracionado->orc_status));

        if (!isset($proximosStatus[$statusAtual])) {
            return back()->with(
                'error',
                'Todos os status deste orçamento fracionado já foram concluídos.'
            );
        }

        DB::transaction(function () use ($fracionado, $proximosStatus, $statusAtual) {

            $fracionado->update([
                'orc_status' => $proximosStatus[$statusAtual],
            ]);

            $this->sincronizarStatusFinanceiro(
                $fracionado->orcamento_id_orcamento
            );
        });

        return back()->with(
            'success',
            'Status do orçamento fracionado atualizado com sucesso!'
        );
    }

    public function destroy($id)
    {
        $orcamentoFracionado = OrcamentoFracionado::findOrFail($id);

        if ($orcamentoFracionado->orc_status !== 'pendente') {
            return back()->with(
                'error',
                'Não é possível excluir um orçamento fracionado que já avançou do status pendente.'
            );
        }

        DB::transaction(function () use ($orcamentoFracionado) {

            $orcamentoId =
                $orcamentoFracionado->orcamento_id_orcamento;

            foreach (
                $orcamentoFracionado->detalhesOrcamentoFracionado
                as $detalhe
            ) {

                if (method_exists($detalhe, 'customizacoes')) {
                    $detalhe->customizacoes()->delete();
                }

                $detalhe->delete();
            }

            $orcamentoFracionado->delete();

            $fracionados = OrcamentoFracionado::where(
                'orcamento_id_orcamento',
                $orcamentoId
            )
                ->orderBy('orc_fracao')
                ->get();

            foreach ($fracionados as $index => $fracionado) {
                $fracionado->update([
                    'orc_fracao' => $index + 1
                ]);
            }
        });

        return redirect()
            ->back()
            ->with(
                'success',
                'Orçamento fracionado excluído!'
            );
    }
}
