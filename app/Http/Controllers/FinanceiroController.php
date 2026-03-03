<?php

namespace App\Http\Controllers;

use App\Models\Financeiro;
use App\Models\Orcamento;
use App\Models\StatusMercadoria;
use App\Models\LogStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;


class FinanceiroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $financeiro = Financeiro::with(['logs.status'])->get();

        return view('view_financeiro.index', compact('financeiro'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $orcamentoToCopy = null;
        if ($request->has('orcamento_id')) {
            $orcamentoToCopy = Orcamento::find($request->input('orcamento_id'));
        }

        return view('view_financeiro.create', compact('orcamentoToCopy'));
    }

    public function prosseguir(Request $request, $id)
    {
        $financeiro = Financeiro::findOrFail($id);

        // Buscar todos os logs daquele orçamento
        $logs = LogStatus::where('log_id_orcamento', $financeiro->orcamento_id_orcamento)
            ->orderBy('status_mercadoria_id_status', 'asc')
            ->get();

        // Encontrar o primeiro log com log_situacao = 0
        $proximoLog = $logs->firstWhere('log_situacao', 0);

        if (!$proximoLog) {
            return redirect()
                ->route('financeiro.index')
                ->with('error', 'Todos os status já estão concluídos.');
        }

        // Ativar o próximo status
        $proximoLog->update([
            'log_situacao' => 1
        ]);

        // Buscar o nome do status correspondente
        $statusNome = StatusMercadoria::where('id_status_merc', $proximoLog->status_mercadoria_id_status)
            ->value('status_merc_nome');

        // Atualizar o campo fin_status no financeiro
        $financeiro->update([
            'fin_status' => $statusNome
        ]);

        return redirect()
            ->route('financeiro.index')
            ->with('success', 'Status atualizado com sucesso!');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $financeiro = Financeiro::findOrFail($id);
        return view('view_financeiro.show', compact('financeiro'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $financeiro = Financeiro::findOrFail($id);

        return view('view_financeiro.edit', compact('financeiro'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
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
