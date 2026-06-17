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

        $query = Orcamento::with('clienteOrcamento')
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

        $filtroVencimento = $request->filtro_vencimento ?? 'ativos';

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

        return view('view_orcamento.index', compact(
            'orcamentos',
            'clienteSelecionado'
        ));
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
                'cliente_orcamento_id_co' => 'required|exists:cliente_orcamento,id_co',
                'orc_data_inicio' => 'required|date',
                'orc_data_fim' => 'required|date|after:orc_data_inicio',
                'orc_status' => 'required|string|max:20',
                'orc_cod_fabrica' => 'nullable|string|max:60|unique:orcamento,orc_cod_fabrica',
                'orc_cod_interno' => 'nullable|string|max:60|unique:orcamento,orc_cod_interno',
                'anotacoes.*' => 'nullable|string|max:1000',
                'orc_anotacao_geral' => 'nullable|string|max:1000',
            ], [
                'orc_data_fim.after' => 'A data final deve ser maior que a data inicial.',
                'orc_cod_fabrica.unique' => 'Este código de fábrica já está cadastrado.',
                'orc_cod_interno.unique' => 'Este código interno já está cadastrado.',
            ]);

            // Concatena os 3 campos de anotação específica em um único campo
            $orc_anotacao_espec = '';
            if ($request->has('anotacoes')) {
                $orc_anotacao_espec = implode("\n", array_map('trim', $request->input('anotacoes')));
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
            ]);

            return redirect()->route('orcamento.index', [
                'cliente_orcamento_id' => $orcamento->cliente_orcamento_id_co
            ])->with('success', 'Orçamento criado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível criar o Orçamento: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Exibe o orçamento especificado.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
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

            $validatedData = $request->validate([
                'cliente_orcamento_id_co' => 'sometimes|required|integer|exists:cliente_orcamento,id_co',
                'orc_data_inicio' => 'sometimes|required|date',
                'orc_data_fim' => 'required|date|after:orc_data_inicio',
                'orc_status' => 'sometimes|required|string|max:20',

                'orc_cod_fabrica' => ['nullable', 'string', 'max:60', Rule::unique('orcamento', 'orc_cod_fabrica')
                    ->ignore($orcamento->id_orcamento, 'id_orcamento')],

                'orc_cod_interno' => ['nullable', 'string', 'max:60', Rule::unique('orcamento', 'orc_cod_interno')
                    ->ignore($orcamento->id_orcamento, 'id_orcamento')],

                'anotacoes.*' => 'nullable|string|max:1000',
                'orc_anotacao_geral' => 'nullable|string|max:1000',
            ], [
                'orc_data_fim.after' => 'A data final deve ser maior que a data inicial.',
                'orc_cod_fabrica.unique' => 'Este código de fábrica já está cadastrado.',
                'orc_cod_interno.unique' => 'Este código interno já está cadastrado.',
            ]);

            // Concatena as anotações específicas
            $orc_anotacao_espec = $orcamento->orc_anotacao_espec;
            if ($request->has('anotacoes')) {
                $orc_anotacao_espec = implode("\n", array_map('trim', $request->input('anotacoes')));
            }

            $orcamento->update([
                'cliente_orcamento_id_co' => $validatedData['cliente_orcamento_id_co'] ?? $orcamento->cliente_orcamento_id_co,
                'orc_data_inicio' => $validatedData['orc_data_inicio'] ?? $orcamento->orc_data_inicio,
                'orc_data_fim' => $validatedData['orc_data_fim'] ?? $orcamento->orc_data_fim,
                'orc_status' => $validatedData['orc_status'] ?? $orcamento->orc_status,
                'orc_cod_fabrica' => $validatedData['orc_cod_fabrica'] ?? $orcamento->orc_cod_fabrica,
                'orc_cod_interno' => $validatedData['orc_cod_interno'] ?? $orcamento->orc_cod_interno,
                'orc_anotacao_espec' => $orc_anotacao_espec,
                'orc_anotacao_geral' => $validatedData['orc_anotacao_geral'] ?? $orcamento->orc_anotacao_geral,
            ]);

            $orcamento->refresh(); // Atualiza a instância com os dados do banco

            // Se o status for "aprovado", cria o financeiro
            if ($orcamento->orc_status === 'aprovado') {

                // Evita duplicar financeiro
                $existe = DB::table('financeiro')
                    ->where('orcamento_id_orcamento', $orcamento->id_orcamento)
                    ->first();

                if (!$existe) {

                    // Calcula o total do orçamento
                    $totalGeral = 0;
                    foreach ($orcamento->detalhesOrcamento as $detalhe) {
                        $subtotalDetalhe = $detalhe->det_quantidade * $detalhe->det_valor_unit;
                        foreach ($detalhe->customizacoes as $customizacao) {
                            $subtotalDetalhe += $customizacao->cust_valor;
                        }
                        $totalGeral += $subtotalDetalhe;
                    }

                    // Insere no financeiro
                    $status = \App\Models\StatusMercadoria::find(1);

                    // Criar o financeiro com o nome correto vindo do banco
                    DB::table('financeiro')->insert([
                        'orcamento_id_orcamento' => $orcamento->id_orcamento,
                        'id_orcamento' => $orcamento->id_orcamento,
                        'id_cliente' => $orcamento->cliente_orcamento_id_co,
                        'fin_nome_cliente' => $orcamento->clienteOrcamento->clie_orc_nome,
                        'fin_valor_total' => $totalGeral,
                        'fin_status' => $status->status_merc_nome, // Aguardando Pagamento
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);


                    // Busca todos os status da mercadoria (1 até 6)
                    $statusList = DB::table('status_mercadoria')
                        ->orderBy('id_status_merc', 'ASC')
                        ->get();

                    $primeiro = true; // flag para controlar o primeiro status

                    foreach ($statusList as $status) {
                        DB::table('log_status')->insert([
                            'status_mercadoria_id_status' => $status->id_status_merc,
                            'log_id_orcamento'           => $orcamento->id_orcamento,
                            'log_id_cliente'             => $orcamento->cliente_orcamento_id_co,
                            'log_nome_status'            => $status->status_merc_nome,
                            'log_situacao'               => $primeiro ? 1 : 0,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        $primeiro = false; // depois do primeiro insert, tudo vira 0
                    }


                    // Redireciona direto para financeiro
                    return redirect()->route('financeiro.index')
                        ->with('success', 'Orçamento aprovado e financeiro criado com sucesso!');
                }
            }

            // Se não criou financeiro, redireciona para lista de orçamentos
            return redirect()->route('orcamento.index', [
                'cliente_orcamento_id' => $orcamento->cliente_orcamento_id_co
            ])->with('success', 'Orçamento criado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível atualizar o Orçamento: ' . $e->getMessage())->withInput();
        }
    }


    public function destroy($id)
    {
        $orcamento = Orcamento::findOrFail($id);

        if (!in_array($orcamento->orc_status, ['pendente', 'rejeitado'])) {
            return redirect()
                ->back()
                ->with('error', 'Somente orçamentos pendentes ou rejeitados podem ser excluídos.');
        }

        foreach ($orcamento->detalhesOrcamento as $detalhe) {

            foreach ($detalhe->customizacoes as $customizacao) {

                if ($customizacao->cust_imagem) {

                    $caminho = public_path('images_customizacoes/' . $customizacao->cust_imagem);

                    if (File::exists($caminho)) {
                        File::delete($caminho);
                    }
                }
            }

            $detalhe->customizacoes()->delete();
        }

        $orcamento->detalhesOrcamento()->delete();

        $orcamento->delete();

        return redirect()->route('orcamento.index', [
            'cliente_orcamento_id' => $orcamento->cliente_orcamento_id_co
        ])->with('success', 'Orçamento excluído com sucesso!');
    }
}
