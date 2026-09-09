<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\OrcamentoFracionado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Exception;

class OrcamentoFracionadoController extends Controller
{
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

        return view(
            'view_orcamento_fracionado.index',
            compact('orcamento')
        );
    }
    public function store($orcamento)
    {
        $orcamentoId = $orcamento;

        DB::transaction(function () use ($orcamentoId) {

            $orcamento = Orcamento::findOrFail($orcamentoId);


            $numeroFracao = OrcamentoFracionado::where(
                'orcamento_id_orcamento',
                $orcamento->id_orcamento
            )->max('orc_fracao') ?? 0;

            OrcamentoFracionado::create([
                'orcamento_id_orcamento' => $orcamento->id_orcamento,
                'orc_fracao' => $numeroFracao + 1,
                'cliente_orcamento_id_co' => $orcamento->cliente_orcamento_id_co,
                'orc_data_inicio' => $orcamento->orc_data_inicio,
                'orc_data_fim' => $orcamento->orc_data_fim,
                'orc_status' => "pendente",
                'orc_anotacao_espec' => $orcamento->orc_anotacao_espec,
                'orc_anotacao_geral' => $orcamento->orc_anotacao_geral,
                'orc_cod_fabrica' => null,
                'orc_cod_interno' => null,
                'orc_desconto_tipo' => $orcamento->orc_desconto_tipo,
                'orc_desconto_valor' => 0,
                'orc_desconto_motivo' => null,
            ]);
        });

        return redirect()
            ->route('orcamento.fracionado.index', $orcamentoId)
            ->with('success', 'Orçamento fracionado criado com sucesso!');
    }


    public function edit($id)
    {
        $orcamentoFracionado = OrcamentoFracionado::with([
            'clienteOrcamento',
            'orcamento'
        ])->findOrFail($id);

        $orcamento = $orcamentoFracionado->orcamento;

        $clienteSelecionado = $orcamentoFracionado->clienteOrcamento;

        return view('view_orcamento_fracionado.edit', compact(
            'orcamentoFracionado',
            'orcamento',
            'clienteSelecionado'
        ));
    }

    public function update(Request $request, $id)
    {
        try {
            $orcamento = OrcamentoFracionado::findOrFail($id);
            $statusAnterior = $orcamento->orc_status;

            $validatedData = $request->validate([
                'orc_data_inicio' => ['required', 'date',],
                'orc_data_fim' => ['required', 'date', 'after:orc_data_inicio',],
                'orc_status' => ['required', 'string', 'in:pendente,para aprovacao,aprovado,finalizado,rejeitado',],
                'orc_cod_fabrica' => [
                    'nullable',
                    'string',
                    'max:60',
                    Rule::unique('orcamento_fracionado', 'orc_cod_fabrica')
                        ->ignore($orcamento->id_orcamento_fracionado, 'id_orcamento_fracionado'),
                ],
                'orc_cod_interno' => [
                    'nullable',
                    'string',
                    'max:60',
                    Rule::unique('orcamento_fracionado', 'orc_cod_interno')
                        ->ignore($orcamento->id_orcamento_fracionado, 'id_orcamento_fracionado'),
                ],
                'orc_anotacao_geral' => ['nullable', 'string', 'max:1000',],
                'orc_motivo_rejeicao' => ['nullable', 'string', 'max:1000', 'required_if:orc_status,rejeitado',],
            ], [
                'orc_data_inicio.required' => 'A data de início é obrigatória.',
                'orc_data_fim.required' => 'A data de fim é obrigatória.',
                'orc_data_fim.after' => 'A data final deve ser maior que a data inicial.',
                'orc_status.required' => 'Selecione um status.',
                'orc_status.in' => 'O status selecionado é inválido.',
                'orc_cod_fabrica.unique' => 'Este código de fábrica já está cadastrado neste orçamento fracionado.',
                'orc_cod_interno.unique' => 'Este código interno já está cadastrado neste orçamento fracionado.',
                'orc_motivo_rejeicao.required_if' => 'Informe o motivo da rejeição.',
                'orc_motivo_rejeicao.max' => 'O motivo da rejeição não pode ultrapassar 1000 caracteres.',
            ]);

            $novoStatus = $validatedData['orc_status'];

            if ($statusAnterior === 'finalizado' && $novoStatus !== 'finalizado') {
                return back()
                    ->with('error', 'Este orçamento fracionado já está finalizado e seu status não pode mais ser alterado.')
                    ->withInput();
            }

            if ($statusAnterior === 'rejeitado' && $novoStatus !== 'rejeitado') {
                return back()
                    ->with('error', 'Este orçamento fracionado foi rejeitado e seu status não pode mais ser alterado.')
                    ->withInput();
            }

            if (
                $statusAnterior === 'aprovado' &&
                !in_array($novoStatus, ['aprovado', 'finalizado', 'rejeitado'])
            ) {
                return back()
                    ->with('error', 'Um orçamento fracionado aprovado só pode ser finalizado ou rejeitado.')
                    ->withInput();
            }

            $orcamento->update([
                'orc_data_inicio' => $validatedData['orc_data_inicio'],
                'orc_data_fim' => $validatedData['orc_data_fim'],
                'orc_status' => $novoStatus,
                'orc_cod_fabrica' => $validatedData['orc_cod_fabrica'] ?? null,
                'orc_cod_interno' => $validatedData['orc_cod_interno'] ?? null,
                'orc_anotacao_geral' => $validatedData['orc_anotacao_geral'] ?? null,
                'orc_motivo_rejeicao' => $novoStatus === 'rejeitado'
                    ? ($validatedData['orc_motivo_rejeicao'] ?? null)
                    : null,
            ]);

            return redirect()
                ->route('orcamento.fracionado.index', $orcamento->orcamento_id_orcamento)
                ->with('success', 'Orçamento fracionado atualizado com sucesso!');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            return back()
                ->with(
                    'error',
                    'Não foi possível atualizar o orçamento fracionado: ' . $e->getMessage()
                )
                ->withInput();
        }
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
            compact('orcamento', 'clienteOrcamento')
        );
    }

    public function aplicarDesconto(Request $request, $id)
    {
        try {
            $orcamentoFracionado = OrcamentoFracionado::with('detalhesOrcamentoFracionado.customizacoes')
                ->findOrFail($id);

            $validated = $request->validate([
                'orc_desconto_tipo'   => 'nullable|in:valor,percentual',
                'orc_desconto_valor'  => 'nullable|numeric|min:0',
                'orc_desconto_motivo' => 'nullable|string|max:255',
            ]);

            // Calcula o total bruto do fracionado (produtos + customizações)
            $totalBruto = 0;

            foreach ($orcamentoFracionado->detalhesOrcamentoFracionado as $detalhe) {
                $quantidade = (int) ($detalhe->det_quantidade ?? 0);

                $totalBruto += $quantidade * (float) ($detalhe->det_valor_unit ?? 0);

                foreach ($detalhe->customizacoes as $customizacao) {
                    $totalBruto += $quantidade * (float) ($customizacao->cust_valor ?? 0);
                }
            }

            // Se o tipo não veio, entende-se que o usuário quer remover o desconto
            if (empty($validated['orc_desconto_tipo'])) {
                $orcamentoFracionado->update([
                    'orc_desconto_tipo'   => null,
                    'orc_desconto_valor'  => 0,
                    'orc_desconto_motivo' => null,
                ]);

                return redirect()->back()->with('success', 'Desconto removido com sucesso!');
            }

            $valor = $validated['orc_desconto_valor'] ?? 0;

            if ($validated['orc_desconto_tipo'] === 'percentual' && $valor > 100) {
                return redirect()->back()
                    ->withErrors(['orc_desconto_valor' => 'O percentual não pode ser maior que 100%.'])
                    ->withInput();
            }

            if ($validated['orc_desconto_tipo'] === 'valor' && $valor > $totalBruto) {
                return redirect()->back()
                    ->withErrors(['orc_desconto_valor' => 'O desconto não pode ser maior que o total do orçamento fracionado.'])
                    ->withInput();
            }

            $orcamentoFracionado->update([
                'orc_desconto_tipo'   => $validated['orc_desconto_tipo'],
                'orc_desconto_valor'  => $valor,
                'orc_desconto_motivo' => $validated['orc_desconto_motivo'] ?? null,
            ]);

            return redirect()->back()->with('success', 'Desconto aplicado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível aplicar o desconto: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $orcamentoFracionado = OrcamentoFracionado::findOrFail($id);

        DB::transaction(function () use ($orcamentoFracionado) {

            $orcamentoId = $orcamentoFracionado->orcamento_id_orcamento;

            foreach ($orcamentoFracionado->detalhesOrcamentoFracionado as $detalhe) {

                if (method_exists($detalhe, 'customizacoes')) {
                    $detalhe->customizacoes()->delete();
                }

                $detalhe->delete();
            }

            // Exclui o fracionado
            $orcamentoFracionado->delete();

            // Busca os fracionados restantes
            $fracionados = OrcamentoFracionado::where(
                'orcamento_id_orcamento',
                $orcamentoId
            )
                ->orderBy('orc_fracao')
                ->get();

            // Renumera novamente
            foreach ($fracionados as $index => $fracionado) {
                $fracionado->update([
                    'orc_fracao' => $index + 1
                ]);
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Orçamento fracionado excluído!');
    }
}
