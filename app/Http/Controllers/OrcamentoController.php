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
use App\Helpers\CryptHelper;

class OrcamentoController extends Controller
{
    private const SESSION_KEY = 'orcamento.index_url';
    private const SCROLL_KEY = 'orcamento.index_scroll';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('cliente_orcamento.index'));
    }

    public function previewOrcamento($id)
    {
        $id = CryptHelper::decrypt($id);

        $orcamento = Orcamento::with(
            'detalhesOrcamento.customizacoes',
            'clienteOrcamento'
        )->findOrFail($id);

        $clienteOrcamento = $orcamento->clienteOrcamento;

        return view('view_orcamento.orcamento_pdf', compact('orcamento', 'clienteOrcamento'));
    }

    public function index(Request $request, $id)
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        if ($request->has('scroll')) {
            session([self::SCROLL_KEY => (int) $request->input('scroll')]);
        }

        $clienteId = CryptHelper::decrypt($id);
        $clienteSelecionado = ClienteOrcamento::findOrFail($clienteId);

        $orcamentos = $this->queryOrcamentos($request, $clienteId)
            ->with([
                'clienteOrcamento',
                'detalhesOrcamento.customizacoes'
            ])
            ->orderBy('id_orcamento', 'asc')
            ->paginate(10)
            ->withQueryString();

        $urlClienteOrcamento = session(
            'cliente_orcamento.index_url',
            route('cliente_orcamento.index')
        );

        return view('view_orcamento.index', compact(
            'orcamentos',
            'clienteSelecionado',
            'urlClienteOrcamento'
        ));
    }

    private function queryOrcamentos(Request $request, int $clienteId)
    {
        $today = Carbon::now()->startOfDay();

        $query = Orcamento::where('cliente_orcamento_id_co', $clienteId);

        if ($request->filled('id_orcamento')) {
            $query->where('id_orcamento', $request->id_orcamento);
        }

        if ($request->filled('orc_cod_fabrica')) {
            $query->where('orc_cod_fabrica', 'like', '%' . trim($request->orc_cod_fabrica) . '%');
        }

        if ($request->filled('orc_cod_interno')) {
            $query->where('orc_cod_interno', 'like', '%' . trim($request->orc_cod_interno) . '%');
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('orc_data_inicio', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('orc_data_fim', $request->data_fim);
        }

        if ($request->filled('status_query')) {
            $query->where('orc_status', $request->status_query);
        }

        $filtroVencimento = $request->input('filtro_vencimento', 'todos');

        if ($filtroVencimento === 'ativos') {
            $query->where('orc_status', '!=', 'rejeitado')
                ->where(function ($q) use ($today) {
                    $q->whereIn('orc_status', ['aprovado', 'finalizado'])
                        ->orWhere('orc_data_fim', '>=', $today);
                });
        } elseif ($filtroVencimento === 'vencidos') {
            $query->where(function ($q) use ($today) {
                $q->where(function ($sub) use ($today) {
                    $sub->whereIn('orc_status', ['pendente', 'para aprovacao'])
                        ->where('orc_data_fim', '<', $today);
                })->orWhere('orc_status', 'rejeitado');
            });
        }

        return $query;
    }

    public function gerarOrcamento($id)
    {
        $id = CryptHelper::decrypt($id);

        $orcamento = Orcamento::with(
            'detalhesOrcamento.customizacoes',
            'clienteOrcamento'
        )->findOrFail($id);

        $clienteOrcamento = $orcamento->clienteOrcamento;

        return view('view_orcamento.gerar_orcamento', [
            'orcamento' => $orcamento,
            'clienteOrcamento' => $clienteOrcamento,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }


    public function gerarOrcamentoPDF($id)
    {
        $id = CryptHelper::decrypt($id);

        $orcamento = Orcamento::with(
            'detalhesOrcamento.customizacoes',
            'clienteOrcamento'
        )->findOrFail($id);

        $clienteOrcamento = $orcamento->clienteOrcamento;

        $pdf = Pdf::loadView(
            'view_orcamento.orcamento_pdf',
            compact('orcamento', 'clienteOrcamento')
        );

        $fileName = 'Orçamento - ' . $clienteOrcamento->clie_orc_nome . '.pdf';

        return $pdf->download($fileName);
    }

    public function create($id)
    {
        $clienteId = CryptHelper::decrypt($id);
        $clienteSelecionado = ClienteOrcamento::findOrFail($clienteId);

        return view('view_orcamento.create', [
            'clienteSelecionado' => $clienteSelecionado,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'cliente_orcamento_id_co' => [
                    'required',
                    'integer',
                    'exists:cliente_orcamento,id_co',
                ],
                'orc_data_inicio' => [
                    'required',
                    'date',
                ],
                'orc_data_fim' => [
                    'required',
                    'date',
                    'after:orc_data_inicio',
                ],
                'orc_status' => [
                    'required',
                    'string',
                    'in:pendente,para aprovacao,rejeitado',
                ],
                'orc_cod_fabrica' => [
                    'nullable',
                    'string',
                    'max:60',
                    'unique:orcamento,orc_cod_fabrica',
                    Rule::unique('orcamento_fracionado', 'orc_cod_fabrica'),
                ],
                'orc_cod_interno' => [
                    'nullable',
                    'string',
                    'max:60',
                    'unique:orcamento,orc_cod_interno',
                    Rule::unique('orcamento_fracionado', 'orc_cod_interno'),
                ],
                'anotacoes' => [
                    'nullable',
                    'array',
                ],
                'anotacoes.*' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
                'orc_anotacao_geral' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
                'orc_motivo_rejeicao' => [
                    'nullable',
                    'string',
                    'max:1000',
                    'required_if:orc_status,rejeitado',
                ],
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

            return redirect($this->urlIndex())
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

    public function aplicarDesconto(Request $request, $id)
    {
        try {
            $id = CryptHelper::decrypt($id);
            $orcamento = Orcamento::findOrFail($id);
            if (in_array($orcamento->orc_status, ['aprovado', 'rejeitado', 'finalizado'])) {
                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Não é permitido alterar o desconto de um orçamento aprovado, rejeitado ou finalizado.'
                    );
            }

            $temDesconto = $request->filled('orc_desconto_tipo');

            $validatedData = $request->validate([
                'orc_desconto_tipo' => [
                    'nullable',
                    'string',
                    'in:valor,percentual',
                ],
                'orc_desconto_valor' => [
                    Rule::requiredIf($temDesconto),
                    'nullable',
                    'numeric',
                    'gt:0',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->input('orc_desconto_tipo') === 'percentual' && $value !== null && $value > 100) {
                            $fail('O percentual de desconto não pode ser maior que 100%.');
                        }
                    },
                ],
                'orc_desconto_motivo' => [
                    Rule::requiredIf($temDesconto),
                    'nullable',
                    'string',
                    'max:255',
                ],
            ], [
                'orc_desconto_tipo.in' => 'Tipo de desconto inválido.',
                'orc_desconto_valor.required' => 'Informe o valor do desconto.',
                'orc_desconto_valor.numeric' => 'O valor do desconto deve ser um número válido.',
                'orc_desconto_valor.gt' => 'O valor do desconto deve ser maior que zero.',
                'orc_desconto_motivo.required' => 'Informe o motivo do desconto.',
            ]);

            if (!$temDesconto) {
                // "Sem desconto" selecionado -> limpa tudo
                $orcamento->update([
                    'orc_desconto_tipo' => null,
                    'orc_desconto_valor' => null,
                    'orc_desconto_motivo' => null,
                ]);
            } else {
                $orcamento->update([
                    'orc_desconto_tipo' => $validatedData['orc_desconto_tipo'],
                    'orc_desconto_valor' => $validatedData['orc_desconto_valor'],
                    'orc_desconto_motivo' => $validatedData['orc_desconto_motivo'],
                ]);
            }

            return redirect()
                ->back()
                ->with('success', 'Desconto atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Não foi possível aplicar o desconto: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $id = CryptHelper::decrypt($id);
        $orcamento = Orcamento::findOrFail($id);
        $clientesOrcamento = ClienteOrcamento::all();
        $clienteSelecionado = $orcamento->clienteOrcamento;

        $financeiroPendente = DB::table('financeiro')
            ->where('orcamento_id_orcamento', $orcamento->id_orcamento)
            ->where('fin_status', '!=', 'entregue')
            ->exists();

        return view('view_orcamento.edit', [
            'orcamento' => $orcamento,
            'clientesOrcamento' => $clientesOrcamento,
            'financeiroPendente' => $financeiroPendente,
            'clienteSelecionado' => $clienteSelecionado,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }


    public function update(Request $request, $id)
    {
        try {
            $id = CryptHelper::decrypt($id);
            $orcamento = Orcamento::findOrFail($id);
            $statusAnterior = $orcamento->orc_status;

            $validatedData = $request->validate([
                'cliente_orcamento_id_co' => [
                    'sometimes',
                    'required',
                    'integer',
                    'exists:cliente_orcamento,id_co',
                ],
                'orc_data_inicio' => [
                    'sometimes',
                    'required',
                    'date',
                ],
                'orc_data_fim' => [
                    'required',
                    'date',
                    'after:orc_data_inicio',
                ],
                'orc_status' => [
                    'sometimes',
                    'required',
                    'string',
                    'in:pendente,para aprovacao,aprovado,finalizado,rejeitado',
                ],
                'orc_cod_fabrica' => [
                    'nullable',
                    'string',
                    'max:60',
                    Rule::unique('orcamento', 'orc_cod_fabrica')
                        ->ignore(
                            $orcamento->id_orcamento,
                            'id_orcamento'
                        ),
                    Rule::unique(
                        'orcamento_fracionado',
                        'orc_cod_fabrica'
                    ),
                ],
                'orc_cod_interno' => [
                    'nullable',
                    'string',
                    'max:60',
                    Rule::unique('orcamento', 'orc_cod_interno')
                        ->ignore(
                            $orcamento->id_orcamento,
                            'id_orcamento'
                        ),
                    Rule::unique(
                        'orcamento_fracionado',
                        'orc_cod_interno'
                    ),
                ],
                'anotacoes' => [
                    'nullable',
                    'array',
                ],
                'anotacoes.*' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
                'orc_anotacao_geral' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
                'orc_motivo_rejeicao' => [
                    'nullable',
                    'string',
                    'max:1000',
                    'required_if:orc_status,rejeitado',
                ],
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
                    ->with(
                        'error',
                        'Este orçamento já está finalizado e seu status não pode mais ser alterado.'
                    )
                    ->withInput();
            }

            if ($statusAnterior === 'rejeitado' && $novoStatus !== 'rejeitado') {
                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Este orçamento foi rejeitado e seu status não pode mais ser alterado.'
                    )
                    ->withInput();
            }

            if (
                $statusAnterior === 'aprovado' &&
                !in_array($novoStatus, ['aprovado', 'finalizado', 'rejeitado'])
            ) {
                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Um orçamento aprovado só pode ser finalizado ou rejeitado.'
                    )
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

                'orc_cod_fabrica' => array_key_exists(
                    'orc_cod_fabrica',
                    $validatedData
                )
                    ? $validatedData['orc_cod_fabrica']
                    : $orcamento->orc_cod_fabrica,

                'orc_cod_interno' => array_key_exists(
                    'orc_cod_interno',
                    $validatedData
                )
                    ? $validatedData['orc_cod_interno']
                    : $orcamento->orc_cod_interno,

                'orc_anotacao_espec' => $orc_anotacao_espec,

                'orc_anotacao_geral' => array_key_exists(
                    'orc_anotacao_geral',
                    $validatedData
                )
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
                    ->where(
                        'orcamento_id_orcamento',
                        $orcamento->id_orcamento
                    )
                    ->exists();

                if (!$existeFinanceiro) {
                    $orcamento->load([
                        'detalhesOrcamento.customizacoes',
                        'clienteOrcamento'
                    ]);

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

                    return redirect($this->urlIndex())
                        ->with(
                            'success',
                            'Orçamento aprovado e financeiro criado com sucesso!'
                        );
                }
            }

            return redirect($this->urlIndex())
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

    public function destroy(Request $request, $id)
    {
        try {
            $id = CryptHelper::decrypt($id);
            $orcamento = Orcamento::findOrFail($id);

            if (!in_array($orcamento->orc_status, ['pendente', 'rejeitado'])) {
                return redirect()->back()
                    ->with('error', 'Somente orçamentos pendentes ou rejeitados podem ser excluídos.');
            }

            $existeFinanceiro = DB::table('financeiro')
                ->where('orcamento_id_orcamento', $orcamento->id_orcamento)
                ->exists();

            if ($existeFinanceiro) {
                return redirect()->back()
                    ->with('error', 'Este orçamento não pode ser excluído porque possui um registro financeiro vinculado.');
            }

            $clienteId = $orcamento->cliente_orcamento_id_co;

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
            $orcamento->delete();

            $urlVoltar = url()->previous();
            $url = parse_url($urlVoltar);

            parse_str($url['query'] ?? '', $query);

            $paginaAtual = max(1, (int) ($query['page'] ?? 1));

            if ($paginaAtual > 1) {
                $requestFiltros = Request::create($urlVoltar, 'GET');

                $totalRestante = $this->queryOrcamentos(
                    $requestFiltros,
                    $clienteId
                )->count();

                $ultimaPagina = max(1, (int) ceil($totalRestante / 2));

                if ($paginaAtual > $ultimaPagina) {
                    $query['page'] = $ultimaPagina;
                    $urlVoltar = ($url['path'] ?? '') . '?' . http_build_query($query);
                }
            }

            return redirect($urlVoltar)
                ->with('success', 'Orçamento excluído com sucesso!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível excluir o orçamento porque existem registros vinculados a ele.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível excluir o orçamento: ' . $e->getMessage());
        }
    }
}
