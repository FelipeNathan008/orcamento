<?php

namespace App\Http\Controllers;

use App\Models\ContaBancaria;
use App\Models\Financeiro;
use App\Models\Orcamento;
use App\Models\FormaPagamento;
use App\Models\StatusMercadoria;
use App\Models\LogStatus;
use App\Models\Movimentacao;
use App\Models\TipoFluxoCaixa;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


class FinanceiroController extends Controller
{

    public function index(Request $request)
    {
        $query = Financeiro::with(['logs.status']);

        if ($request->filled('id_financeiro')) {
            $query->where('id_fin', $request->id_financeiro);
        }

        if ($request->filled('id_orcamento')) {
            $query->where('orcamento_id_orcamento', $request->id_orcamento);
        }

        if ($request->filled('cliente')) {
            $query->where(
                'fin_nome_cliente',
                'like',
                '%' . trim($request->cliente) . '%'
            );
        }

        if ($request->filled('status')) {
            $query->where('fin_status', $request->status);
        }

        $financeiro = $query
            ->orderBy('id_fin', 'desc')
            ->paginate(10)
            ->withQueryString();

        foreach ($financeiro as $fin) {
            $fin->temStatusPendente = $fin->logs->contains('log_situacao', 0);
        }

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
            'view_financeiro.index',
            compact(
                'tipoDespesaUP',
                'movSaida',
                'financeiro',
                'tipos',
                'movimentacoes',
                'contas'
            )
        );
    }

    public function create(Request $request)
    {
        $orcamentoToCopy = null;
        if ($request->has('orcamento_id')) {
            $orcamentoToCopy = Orcamento::find($request->input('orcamento_id'));
        }

        return view('view_financeiro.create', compact('orcamentoToCopy'));
    }

    private function validarFracionamento(Orcamento $orcamento): ?string
    {
        $orcamento->loadMissing('fracionados');

        if ($orcamento->fracionados->isEmpty()) {
            return null; // sem fracionamento, prossegue normalmente
        }

        $valorBrutoOriginal = (float) ($orcamento->total_bruto ?? 0);

        $valorFracionado = $orcamento->fracionados->sum(
            fn($f) => (float) ($f->total_bruto ?? 0)
        );

        $diferenca = $valorBrutoOriginal - $valorFracionado;

        if (abs($diferenca) < 0.01) {
            return null; // valores conferem, pode prosseguir
        }

        if ($diferenca > 0) {
            return sprintf(
                'Não é possível prosseguir com o status. Ainda falta R$ %s para que os valores dos orçamentos fracionados correspondam ao orçamento original.',
                number_format($diferenca, 2, ',', '.')
            );
        }

        return sprintf(
            'Não é possível prosseguir com o status. Os orçamentos fracionados ultrapassaram o valor original em R$ %s.',
            number_format(abs($diferenca), 2, ',', '.')
        );
    }

    public function prosseguir(Request $request, string $id)
    {
        $financeiro = Financeiro::with('orcamento.fracionados')
            ->findOrFail($id);

        $orcamento = $financeiro->orcamento;

        if ($orcamento) {

            $erroFracionamento = $this->validarFracionamento($orcamento);

            if ($erroFracionamento) {
                return back()->with('error', $erroFracionamento);
            }
        }

        $formas = FormaPagamento::where(
            'financeiro_id_fin',
            $financeiro->id_fin
        )->get();

        $valorTotal = (float) $financeiro->fin_valor_total;

        $valorEntrada = $formas
            ->where('forma_prazo', 'Entrada')
            ->sum('forma_valor');

        $valorNegociado = $formas
            ->where('forma_prazo', '!=', 'Entrada')
            ->sum('forma_valor');

        $valorCompletado = $valorEntrada + $valorNegociado;

        $pagamentoCompleto = abs(
            $valorCompletado - $valorTotal
        ) < 0.01;

        if (!$pagamentoCompleto) {
            return back()->with(
                'error',
                'Não é possível prosseguir. O Valor Entrada + Valor Negociado deve ser igual ao Valor Total.'
            );
        }

        $logs = LogStatus::where(
            'log_id_orcamento',
            $financeiro->orcamento_id_orcamento
        )
            ->orderBy('status_mercadoria_id_status', 'asc')
            ->get();

        $proximoLog = $logs->firstWhere('log_situacao', 0);

        if (!$proximoLog) {
            return redirect()
                ->route('financeiro.index')
                ->with('error', 'Todos os status já estão concluídos.');
        }

        $proximoLog->update([
            'log_situacao' => 1
        ]);

        $statusNome = StatusMercadoria::where(
            'id_status_merc',
            $proximoLog->status_mercadoria_id_status
        )->value('status_merc_nome');

        $financeiro->update([
            'fin_status' => $statusNome
        ]);

        return redirect()
            ->route('financeiro.index')
            ->with('success', 'Status atualizado com sucesso!');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'orcamento_id_orcamento' => 'required|integer|exists:orcamento,id_orcamento',
                'id_orcamento' => 'required|integer',
                'id_cliente' => 'required|integer',
                'fin_nome_cliente' => 'required|string|max:90',
                'fin_valor_total' => 'required|numeric|min:0',
                'fin_status' => 'required|string|max:45',
            ]);

            Financeiro::create($validatedData);

            return redirect()->route('financeiro.index')->with('success', 'Registro financeiro criado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível criar o registro financeiro: ' . $e->getMessage())->withInput();
        }
    }


    public function show($id)
    {
        $financeiro = Financeiro::findOrFail($id);
        return view('view_financeiro.show', compact('financeiro'));
    }


    public function edit($id)
    {
        $financeiro = Financeiro::findOrFail($id);

        return view('view_financeiro.edit', compact('financeiro'));
    }


    public function update(Request $request, $id)
    {
        try {
            $financeiro = Financeiro::findOrFail($id);

            $validatedData = $request->validate([
                'orcamento_id_orcamento' => 'sometimes|required|integer|exists:orcamento,id_orcamento',
                'id_orcamento' => 'sometimes|required|integer',
                'id_cliente' => 'sometimes|required|integer',
                'fin_nome_cliente' => 'sometimes|required|string|max:90',
                'fin_valor_total' => 'sometimes|required|numeric|min:0',
                'fin_status' => 'sometimes|required|string|max:45',
            ]);

            $financeiro->update($validatedData);

            return redirect()->route('financeiro.show', $financeiro->id_fin)->with('success', 'Registro financeiro atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível atualizar o registro financeiro: ' . $e->getMessage())->withInput();
        }
    }


    public function destroy($id)
    {
        // Buscar financeiro
        $financeiro = Financeiro::findOrFail($id);

        // Apagar logs vinculados ao orçamento
        DB::table('log_status')
            ->where('log_id_orcamento', $financeiro->id_orcamento)
            ->delete();

        // Apagar registro do financeiro
        $financeiro->delete();

        return redirect()->route('financeiro.index')
            ->with('success', 'Registro financeiro excluídos com sucesso!');
    }
}
