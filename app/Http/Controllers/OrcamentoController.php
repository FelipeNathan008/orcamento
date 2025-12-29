<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\ClienteOrcamento;
use App\Models\DetalheOrcamento;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\AnexarOrcamentoMailable;
use Exception;
use Laravel\Pail\ValueObjects\Origin\Console;
use Throwable; // Adicionado para capturar todos os tipos de erros
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrcamentoController extends Controller
{
    /**
     * Exibe a lista de orçamentos, com opções de filtro.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */

    public function previewOrcamento($id)
    {
        $orcamento = Orcamento::with('detalhesOrcamento.customizacoes', 'clienteOrcamento')->findOrFail($id);
        $clienteOrcamento = $orcamento->clienteOrcamento;

        // Retorna a mesma view usada no PDF, mas como HTML normal
        return view('view_orcamento.orcamento_pdf', compact('orcamento', 'clienteOrcamento'));
    }

    // Index normal
    public function index()
    {
        $today = Carbon::now()->startOfDay();

        $orcamentos = Orcamento::with('clienteOrcamento')
            ->where(function ($query) use ($today) {
                // Todos que não estão vencidos ou não são status pendente/para aprovacao
                $query->where('orc_data_fim', '>=', $today)
                    ->orWhereNotIn('orc_status', ['pendente', 'para aprovacao', 'rejeitado']);
            })
            ->get();

        return view('view_orcamento.index', compact('orcamentos'));
    }

    // Tela de Orçamentos Vencidos
    public function indexfunil()
    {
        $today = Carbon::now()->startOfDay();

        $orcamentos = Orcamento::with('clienteOrcamento')
            ->where(function ($query) use ($today) {
                // Pendente ou Para Aprovação vencidos
                $query->whereIn('orc_status', ['pendente', 'para aprovacao'])
                    ->where('orc_data_fim', '<', $today);
            })
            ->orWhere('orc_status', 'rejeitado') // sempre pega rejeitados
            ->get();

        return view('view_orcamento.index_funil', compact('orcamentos'));
    }




    /**
     * Exibe a view de geração do orçamento.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function gerarOrcamento($id)
    {
        // Carrega o orçamento e suas relações
        $orcamento = Orcamento::with('detalhesOrcamento.customizacoes', 'clienteOrcamento')->findOrFail($id);

        // Acessa a relação diretamente do modelo carregado, evitando uma nova consulta
        $clienteOrcamento = $orcamento->clienteOrcamento;

        // Passa as duas variáveis para a view
        return view('view_orcamento.gerar_orcamento', compact('orcamento', 'clienteOrcamento'));
    }

    /**
     * Gera o orçamento em PDF e força o download.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
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


    /**
     * Exibe o formulário para criar um novo orçamento.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $clientesOrcamento = ClienteOrcamento::all();
        $selectedClienteOrcamentoId = $request->query('cliente_orcamento_id');

        return view('view_orcamento.create', compact('clientesOrcamento', 'selectedClienteOrcamentoId'));
    }

    /**
     * Armazena um novo orçamento no banco de dados.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'cliente_orcamento_id_co' => 'required|exists:cliente_orcamento,id_co',
                'orc_data_inicio' => 'required|date',
                'orc_data_fim' => 'required|date|after_or_equal:orc_data_inicio',
                'orc_status' => 'required|string|max:20',
                'anotacoes.*' => 'nullable|string|max:1000',
                'orc_anotacao_geral' => 'nullable|string|max:1000',
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
                'orc_anotacao_espec' => $orc_anotacao_espec,
                'orc_anotacao_geral' => $validatedData['orc_anotacao_geral'] ?? null,
            ]);
            return redirect()->route('detalhes_orcamento.create', ['orcamento_id' => $orcamento->id_orcamento])
                ->with('success', 'Orçamento criado com sucesso! Agora adicione os detalhes.');
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

    /**
     * Exibe o formulário para editar o orçamento especificado.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $orcamento = Orcamento::findOrFail($id);
        $clientesOrcamento = ClienteOrcamento::all();
        return view('view_orcamento.edit', compact('orcamento', 'clientesOrcamento'));
    }

    /**
     * Atualiza o orçamento especificado no banco de dados.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $orcamento = Orcamento::findOrFail($id);

            // Validação dos dados
            $validatedData = $request->validate([
                'cliente_orcamento_id_co' => 'sometimes|required|integer|exists:cliente_orcamento,id_co',
                'orc_data_inicio' => 'sometimes|required|date',
                'orc_data_fim' => 'sometimes|required|date|after_or_equal:orc_data_inicio',
                'orc_status' => 'sometimes|required|string|max:20',
                'anotacoes.*' => 'nullable|string|max:1000',
                'orc_anotacao_geral' => 'nullable|string|max:1000',
            ]);

            // Concatena as anotações específicas
            $orc_anotacao_espec = $orcamento->orc_anotacao_espec;
            if ($request->has('anotacoes')) {
                $orc_anotacao_espec = implode("\n", array_map('trim', $request->input('anotacoes')));
            }

            // Atualiza orçamento
            $orcamento->update([
                'cliente_orcamento_id_co' => $validatedData['cliente_orcamento_id_co'] ?? $orcamento->cliente_orcamento_id_co,
                'orc_data_inicio' => $validatedData['orc_data_inicio'] ?? $orcamento->orc_data_inicio,
                'orc_data_fim' => $validatedData['orc_data_fim'] ?? $orcamento->orc_data_fim,
                'orc_status' => $validatedData['orc_status'] ?? $orcamento->orc_status,
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
                        ]);

                        $primeiro = false; // depois do primeiro insert, tudo vira 0
                    }


                    // Redireciona direto para financeiro
                    return redirect()->route('financeiro.index')
                        ->with('success', 'Orçamento aprovado e financeiro criado com sucesso!');
                }
            }

            // Se não criou financeiro, redireciona para lista de orçamentos
            return redirect()->route('orcamento.index')
                ->with('success', 'Orçamento atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível atualizar o Orçamento: ' . $e->getMessage())->withInput();
        }
    }




    /**
     * Remove o orçamento especificado do banco de dados.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $orcamento = Orcamento::findOrFail($id);
        $orcamento->delete();

        return redirect()->route('orcamento.index')->with('success', 'Orçamento excluído com sucesso!');
    }
}
