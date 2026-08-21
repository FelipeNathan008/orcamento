<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\ClienteOrcamento;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class OrcamentoController extends Controller
{

    public function previewOrcamento($id)
    {
        $orcamento = Orcamento::with('detalhesOrcamento.customizacoes', 'clienteOrcamento')->findOrFail($id);
        $clienteOrcamento = $orcamento->clienteOrcamento;

        // Retorna a mesma view usada no PDF, mas como HTML normal
        return view('view_orcamento.orcamento_pdf', compact('orcamento', 'clienteOrcamento'));
    }

    public function index(Request $request)
    {
        if (!$request->cliente_orcamento_id) {
            return redirect()->route('cliente_orcamento.index')
                ->with('error', 'Selecione um cliente para visualizar os orçamentos.');
        }

        $today = Carbon::now()->startOfDay();

        $clienteSelecionado = ClienteOrcamento::where(
            'id_co',
            $request->cliente_orcamento_id
        )->firstOrFail();

        $query = Orcamento::with([
            'clienteOrcamento',
            'detalhesOrcamento.customizacoes'
        ])
            ->where('cliente_orcamento_id_co', $request->cliente_orcamento_id);

        if ($request->filled('orc_cod_fabrica')) {
            $query->where(
                'orc_cod_fabrica',
                'like',
                '%' . trim($request->orc_cod_fabrica) . '%'
            );
        }

        if ($request->filled('orc_cod_interno')) {
            $query->where(
                'orc_cod_interno',
                'like',
                '%' . trim($request->orc_cod_interno) . '%'
            );
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate(
                'orc_data_inicio',
                $request->data_inicio
            );
        }

        if ($request->filled('data_fim')) {
            $query->whereDate(
                'orc_data_fim',
                $request->data_fim
            );
        }

        if ($request->filled('status_query')) {
            $query->where('orc_status', $request->status_query);
        }

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
                })
                    ->orWhere('orc_status', 'rejeitado');
            });
        }

        $orcamentos = $query
            ->orderBy('orc_cod_fabrica', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('view_orcamento.index', compact('orcamentos', 'clienteSelecionado'));
    }

    public function aplicarDesconto(Request $request, $id)
    {
        try {
            $orcamento = Orcamento::with('detalhesOrcamento.customizacoes')->findOrFail($id);

            $validated = $request->validate([
                'orc_desconto_tipo'   => 'nullable|in:valor,percentual',
                'orc_desconto_valor'  => 'nullable|numeric|min:0',
                'orc_desconto_motivo' => 'nullable|string|max:255',
            ]);

            // Se o tipo não veio, entende-se que o usuário quer remover o desconto
            if (empty($validated['orc_desconto_tipo'])) {
                $orcamento->update([
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

            if ($validated['orc_desconto_tipo'] === 'valor' && $valor > $orcamento->total_bruto) {
                return redirect()->back()
                    ->withErrors(['orc_desconto_valor' => 'O desconto não pode ser maior que o total do orçamento.'])
                    ->withInput();
            }

            $orcamento->update([
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

    public function gerarOrcamento(Request $request, $id)
    {
        $orcamento = Orcamento::with(
            'detalhesOrcamento.customizacoes',
            'clienteOrcamento'
        )->findOrFail($id);

        $clienteOrcamento = $orcamento->clienteOrcamento;

        $cliente_orcamento_id = $request->cliente_orcamento_id;

        return view(
            'view_orcamento.gerar_orcamento',
            compact('orcamento', 'clienteOrcamento', 'cliente_orcamento_id')
        );
    }


    public function gerarOrcamentoPDF($id)
    {
        // Carrega o orçamento e suas relações
        $orcamento = Orcamento::with('detalhesOrcamento.customizacoes', 'clienteOrcamento')->findOrFail($id);
        $clienteOrcamento = $orcamento->clienteOrcamento;

        // Gera a view do PDF com os dados
        $pdf = Pdf::loadView('view_orcamento.orcamento_pdf', compact('orcamento', 'clienteOrcamento'));

        // Define o nome do arquivo para download
        $fileName = 'Orçamento - ' . $clienteOrcamento->clie_orc_nome . '.pdf';

        // Força o download do PDF gerado
        return $pdf->download($fileName);
    }


    public function create(Request $request)
    {

        if (!$request->cliente_orcamento_id) {
            return view('view_orcamento.create', compact(
                'clienteSelecionado'
            ));
        }

        $today = Carbon::now()->startOfDay();

        $clienteSelecionado = ClienteOrcamento::where(
            'id_co',
            $request->cliente_orcamento_id
        )->firstOrFail();

        return view('view_orcamento.create', compact('clienteSelecionado'));
    }


    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'cliente_orcamento_id_co' => ['required', 'integer', 'exists:cliente_orcamento,id_co',],
                'orc_data_inicio' => ['required', 'date',],
                'orc_data_fim' => ['required', 'date', 'after:orc_data_inicio',],
                'orc_status' => ['required', 'string', 'in:pendente,para aprovacao,rejeitado',],
                'orc_cod_fabrica' => ['nullable', 'string', 'max:60', 'unique:orcamento,orc_cod_fabrica',],
                'orc_cod_interno' => ['nullable', 'string', 'max:60', 'unique:orcamento,orc_cod_interno',],
                'anotacoes' => ['nullable', 'array',],
                'anotacoes.*' => ['nullable', 'string', 'max:1000',],
                'orc_anotacao_geral' => ['nullable', 'string', 'max:1000',],
                'orc_motivo_rejeicao' => ['nullable', 'string', 'max:1000', 'required_if:orc_status,rejeitado',],
            ], [
                'orc_data_inicio.required' => 'A data de início é obrigatória.',
                'orc_data_fim.required' => 'A data de fim é obrigatória.',
                'orc_data_fim.after' => 'A data final deve ser maior que a data inicial.',
                'orc_status.required' => 'Selecione um status.',
                'orc_status.in' => 'O status selecionado é inválido.',
                'orc_cod_fabrica.unique' => 'Este código de fábrica já está cadastrado.',
                'orc_cod_interno.unique' => 'Este código interno já está cadastrado.',
                'orc_motivo_rejeicao.required_if' => 'Informe o motivo da rejeição.',
                'orc_motivo_rejeicao.max' => 'O motivo da rejeição não pode ultrapassar 1000 caracteres.',
            ]);

            $orc_anotacao_espec = '';

            if ($request->filled('anotacoes')) {
                $anotacoes = array_map(
                    'trim',
                    array_slice($request->input('anotacoes'), 0, 3)
                );

                $orc_anotacao_espec = implode("\n", $anotacoes);
            }

            $orc_motivo_rejeicao = null;

            if ($validatedData['orc_status'] === 'rejeitado') {
                $orc_motivo_rejeicao = trim($validatedData['orc_motivo_rejeicao']);
            }

            $orcamento = Orcamento::create([
                'cliente_orcamento_id_co' => $validatedData['cliente_orcamento_id_co'],
                'orc_data_inicio' => $validatedData['orc_data_inicio'],
                'orc_data_fim' => $validatedData['orc_data_fim'],
                'orc_status' => $validatedData['orc_status'],
                'orc_cod_fabrica' => $validatedData['orc_cod_fabrica'] ?? null,
                'orc_cod_interno' => $validatedData['orc_cod_interno'] ?? null,
                'orc_anotacao_espec' => $orc_anotacao_espec,
                'orc_anotacao_geral' => $validatedData['orc_anotacao_geral'] ?? null,
                'orc_motivo_rejeicao' => $orc_motivo_rejeicao,
            ]);

            return redirect()
                ->route('orcamento.index', [
                    'cliente_orcamento_id' => $orcamento->cliente_orcamento_id_co
                ])
                ->with('success', 'Orçamento criado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Não foi possível criar o Orçamento: ' . $e->getMessage()
                )
                ->withInput();
        }
    }


    public function show($id)
    {
        $orcamento = Orcamento::with('clienteOrcamento')->findOrFail($id);
        return view('view_orcamento.show', compact('orcamento'));
    }

    public function edit($id)
    {
        $orcamento = Orcamento::findOrFail($id);
        $clientesOrcamento = ClienteOrcamento::all();
        $clienteSelecionado = $orcamento->clienteOrcamento;
        $financeiroPendente = DB::table('financeiro')
            ->where('orcamento_id_orcamento', $orcamento->id_orcamento)
            ->where('fin_status', '!=', 'entregue')
            ->exists();

        return view(
            'view_orcamento.edit',
            compact('orcamento', 'clientesOrcamento', 'financeiroPendente', 'clienteSelecionado')
        );
    }


    public function update(Request $request, $id)
    {
        try {
            $orcamento = Orcamento::findOrFail($id);
            $statusAnterior = $orcamento->orc_status;

            $validatedData = $request->validate([
                'cliente_orcamento_id_co' => ['sometimes', 'required', 'integer', 'exists:cliente_orcamento,id_co',],
                'orc_data_inicio' => ['sometimes', 'required', 'date',],
                'orc_data_fim' => ['required', 'date', 'after:orc_data_inicio',],
                'orc_status' => ['sometimes', 'required', 'string', 'in:pendente,para aprovacao,aprovado,finalizado,rejeitado',],
                'orc_cod_fabrica' => [
                    'nullable',
                    'string',
                    'max:60',
                    Rule::unique('orcamento', 'orc_cod_fabrica')->ignore($orcamento->id_orcamento, 'id_orcamento'),
                ],
                'orc_cod_interno' => [
                    'nullable',
                    'string',
                    'max:60',
                    Rule::unique('orcamento', 'orc_cod_interno')
                        ->ignore($orcamento->id_orcamento, 'id_orcamento'),
                ],
                'anotacoes' => ['nullable', 'array',],
                'anotacoes.*' => ['nullable', 'string', 'max:1000',],
                'orc_anotacao_geral' => ['nullable', 'string', 'max:1000',],
                'orc_motivo_rejeicao' => ['nullable', 'string', 'max:1000', 'required_if:orc_status,rejeitado',],

            ], [
                'orc_data_fim.after' => 'A data final deve ser maior que a data inicial.',
                'orc_cod_fabrica.unique' => 'Este código de fábrica já está cadastrado.',
                'orc_cod_interno.unique' => 'Este código interno já está cadastrado.',
                'orc_motivo_rejeicao.required_if' => 'Informe o motivo da rejeição.',
                'orc_motivo_rejeicao.max' => 'O motivo da rejeição não pode ultrapassar 1000 caracteres.',
            ]);

            $novoStatus = $validatedData['orc_status'] ?? $statusAnterior;

            if (
                $novoStatus === 'aprovado' &&
                (float) $orcamento->total_com_desconto <= 0
            ) {
                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Este orçamento não pode ser aprovado porque seu valor total é R$ 0,00.'
                    )
                    ->withInput();
            }

            if ($statusAnterior === 'finalizado' && $novoStatus !== 'finalizado') {
                return redirect()
                    ->back()
                    ->with('error', 'Este orçamento já está finalizado e seu status não pode mais ser alterado.')
                    ->withInput();
            }

            if ($statusAnterior === 'rejeitado' && $novoStatus !== 'rejeitado') {
                return redirect()
                    ->back()
                    ->with('error', 'Este orçamento foi rejeitado e seu status não pode mais ser alterado.')
                    ->withInput();
            }

            if (
                $statusAnterior === 'aprovado' &&
                !in_array($novoStatus, ['aprovado', 'finalizado', 'rejeitado'])
            ) {
                return redirect()
                    ->back()
                    ->with('error', 'Um orçamento aprovado só pode ser finalizado ou rejeitado.')
                    ->withInput();
            }

            $orc_anotacao_espec = $orcamento->orc_anotacao_espec;

            if ($request->has('anotacoes')) {
                $orc_anotacao_espec = implode(
                    "\n",
                    array_map('trim', $request->input('anotacoes'))
                );
            }

            $orcamento->update([
                'cliente_orcamento_id_co' => $validatedData['cliente_orcamento_id_co']
                    ?? $orcamento->cliente_orcamento_id_co,
                'orc_data_inicio' => $validatedData['orc_data_inicio']
                    ?? $orcamento->orc_data_inicio,
                'orc_data_fim' => $validatedData['orc_data_fim']
                    ?? $orcamento->orc_data_fim,
                'orc_status' => $novoStatus,
                'orc_cod_fabrica' => array_key_exists('orc_cod_fabrica', $validatedData)
                    ? $validatedData['orc_cod_fabrica']
                    : $orcamento->orc_cod_fabrica,
                'orc_cod_interno' => array_key_exists('orc_cod_interno', $validatedData)
                    ? $validatedData['orc_cod_interno']
                    : $orcamento->orc_cod_interno,
                'orc_anotacao_espec' => $orc_anotacao_espec,
                'orc_anotacao_geral' => array_key_exists('orc_anotacao_geral', $validatedData)
                    ? $validatedData['orc_anotacao_geral']
                    : $orcamento->orc_anotacao_geral,
                'orc_motivo_rejeicao' => $novoStatus === 'rejeitado'
                    ? ($validatedData['orc_motivo_rejeicao'] ?? null)
                    : null,
            ]);

            $orcamento->refresh();

            $foiAprovadoAgora =
                $statusAnterior !== 'aprovado' &&
                $novoStatus === 'aprovado';

            if ($foiAprovadoAgora) {
                $existeFinanceiro = DB::table('financeiro')
                    ->where('orcamento_id_orcamento', $orcamento->id_orcamento)
                    ->exists();

                if (!$existeFinanceiro) {
                    $totalGeral = 0;

                    $orcamento->load([
                        'detalhesOrcamento.customizacoes',
                        'clienteOrcamento'
                    ]);

                    foreach ($orcamento->detalhesOrcamento as $detalhe) {
                        $quantidade = (int) ($detalhe->det_quantidade ?? 0);

                        $totalProduto =
                            $quantidade *
                            (float) ($detalhe->det_valor_unit ?? 0);

                        $totalCustomizacoes = 0;

                        foreach ($detalhe->customizacoes as $customizacao) {
                            $valorCustomizacao =
                                (float) ($customizacao->cust_valor ?? 0);

                            $totalCustomizacoes +=
                                $quantidade *
                                $valorCustomizacao;
                        }

                        $totalGeral +=
                            $totalProduto +
                            $totalCustomizacoes;
                    }

                    $status = \App\Models\StatusMercadoria::find(1);

                    DB::table('financeiro')->insert([
                        'orcamento_id_orcamento' => $orcamento->id_orcamento,
                        'id_orcamento' => $orcamento->id_orcamento,
                        'id_cliente' => $orcamento->cliente_orcamento_id_co,
                        'fin_nome_cliente' => $orcamento->clienteOrcamento->clie_orc_nome,
                        'fin_valor_total' => $orcamento->total_com_desconto,
                        'fin_status' => $status->status_merc_nome,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $statusList = DB::table('status_mercadoria')
                        ->orderBy('id_status_merc', 'ASC')
                        ->get();

                    $primeiro = true;

                    foreach ($statusList as $status) {
                        DB::table('log_status')->insert([
                            'status_mercadoria_id_status' => $status->id_status_merc,
                            'log_id_orcamento' => $orcamento->id_orcamento,
                            'log_id_cliente' => $orcamento->cliente_orcamento_id_co,
                            'log_nome_status' => $status->status_merc_nome,
                            'log_situacao' => $primeiro ? 1 : 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $primeiro = false;
                    }

                    return redirect()
                        ->route('financeiro.index')
                        ->with(
                            'success',
                            'Orçamento aprovado e financeiro criado com sucesso!'
                        );
                }
            }

            return redirect()
                ->route('orcamento.index', [
                    'cliente_orcamento_id' => $orcamento->cliente_orcamento_id_co
                ])
                ->with('success', 'Orçamento atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Não foi possível atualizar o Orçamento: ' . $e->getMessage()
                )
                ->withInput();
        }
    }


    public function destroy($id)
    {
        try {
            $orcamento = Orcamento::findOrFail($id);

            if (!in_array($orcamento->orc_status, ['pendente', 'rejeitado'])) {
                return redirect()
                    ->back()
                    ->with('error', 'Somente orçamentos pendentes ou rejeitados podem ser excluídos.');
            }

            $existeFinanceiro = DB::table('financeiro')
                ->where('orcamento_id_orcamento', $orcamento->id_orcamento)
                ->exists();

            if ($existeFinanceiro) {
                return redirect()
                    ->back()
                    ->with('error', 'Este orçamento não pode ser excluído porque possui um registro financeiro vinculado.');
            }

            $orcamento->load('detalhesOrcamento.customizacoes');

            foreach ($orcamento->detalhesOrcamento as $detalhe) {
                foreach ($detalhe->customizacoes as $customizacao) {
                    if ($customizacao->cust_imagem) {
                        $caminho = public_path(
                            'images_customizacoes/' . $customizacao->cust_imagem
                        );

                        if (File::exists($caminho)) {
                            File::delete($caminho);
                        }
                    }
                }

                $detalhe->customizacoes()->delete();
            }

            $orcamento->detalhesOrcamento()->delete();

            $clienteId = $orcamento->cliente_orcamento_id_co;

            $orcamento->delete();

            return redirect()
                ->route('orcamento.index', [
                    'cliente_orcamento_id' => $clienteId
                ])
                ->with('success', 'Orçamento excluído com sucesso!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Não foi possível excluir o orçamento porque existem registros vinculados a ele.'
                );
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Não foi possível excluir o orçamento: ' . $e->getMessage()
                );
        }
    }
}
