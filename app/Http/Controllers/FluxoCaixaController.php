<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FluxoCaixa;
use App\Models\TipoFluxoCaixa;
use App\Models\Movimentacao;
use App\Models\Financeiro;
use App\Models\LogStatus;
use App\Models\StatusMercadoria;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class FluxoCaixaController extends Controller
{
    public function index()
    {
        $fluxos = FluxoCaixa::with(['tipo', 'movimentacao'])->get();
        return view('view_fluxo_caixa.index', compact('fluxos'));
    }

    public function create()
    {
        $tipos = TipoFluxoCaixa::all();
        $movimentacoes = Movimentacao::all();

        return view('view_fluxo_caixa.create', compact('tipos', 'movimentacoes'));
    }

    public function gerarFluxoPdfPorData(Request $request)
    {
        $data = $request->data;

        $fluxos = FluxoCaixa::with(['tipo', 'movimentacao'])
            ->whereDate('flu_data_despesa', $data)
            ->get();

        $pdf = Pdf::loadView('view_fluxo_caixa.pdf', compact('fluxos', 'data'));

        return $pdf->download('Fluxo_Caixa' . $data . '.pdf');
    }

    public function store(Request $request)
    {
        //dd($request);
        $validatedData = $request->validate([
            'flu_data_despesa' => 'required|date',
            'flu_id_tipo' => 'required|integer',
            'flu_id_movimentacao' => 'required|integer',
            'flu_valor' => 'required|numeric',
            'flu_num_doc' => 'nullable|string|max:255',
            'flu_desc' => 'required|string|max:180',
        ]);

        FluxoCaixa::create($validatedData);

        return redirect()->route('fluxo_caixa.index')
            ->with('success', 'Registro de fluxo criado com sucesso!');
    }
    
    public function storeFluxo(Request $request)
    {
        DB::beginTransaction();

        try {

            $validatedData = $request->validate([
                'flu_data_despesa' => 'required|date',
                'flu_id_tipo' => 'required|integer',
                'flu_id_movimentacao' => 'required|integer',
                'flu_valor' => 'required|numeric',
                'flu_num_doc' => 'nullable|string|max:255',
                'flu_desc' => 'required|string|max:180',

                'id_financeiro' => 'required|integer|exists:financeiro,id_fin'
            ]);

            FluxoCaixa::create([
                'flu_data_despesa' => $validatedData['flu_data_despesa'],
                'flu_id_tipo' => $validatedData['flu_id_tipo'],
                'flu_id_movimentacao' => $validatedData['flu_id_movimentacao'],
                'flu_valor' => $validatedData['flu_valor'],
                'flu_num_doc' => $validatedData['flu_num_doc'],
                'flu_desc' => $validatedData['flu_desc'],
            ]);

            $financeiro = Financeiro::findOrFail($validatedData['id_financeiro']);

            $proximoLog = LogStatus::where(
                'log_id_orcamento',
                $financeiro->orcamento_id_orcamento
            )
                ->where('log_situacao', 0)
                ->orderBy('status_mercadoria_id_status')
                ->first();

            if (!$proximoLog) {
                throw new \Exception('Nenhum próximo status encontrado.');
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

            DB::commit();

            return redirect()
                ->route('financeiro.index')
                ->with('success', 'Fluxo salvo e status atualizado com sucesso!');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Erro ao salvar fluxo: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(string $id)
    {
        $fluxo = FluxoCaixa::with(['tipo', 'movimentacao'])->findOrFail($id);
        return view('view_fluxo_caixa.show', compact('fluxo'));
    }

    public function edit(string $id)
    {
        $fluxo = FluxoCaixa::findOrFail($id);
        $tipos = TipoFluxoCaixa::all();
        $movimentacoes = Movimentacao::all();

        return view('view_fluxo_caixa.edit', compact('fluxo', 'tipos', 'movimentacoes'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'flu_data_despesa' => 'required|date',
            'flu_id_tipo' => 'required|integer',
            'flu_id_movimentacao' => 'required|integer',
            'flu_valor' => 'required|numeric',
            'flu_num_doc' => 'nullable|string|max:255',
            'flu_desc' => 'required|string|max:180',
        ]);

        $fluxo = FluxoCaixa::findOrFail($id);
        $fluxo->update($validatedData);

        return redirect()->route('fluxo_caixa.index')
            ->with('success', 'Registro de fluxo atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $fluxo = FluxoCaixa::findOrFail($id);
        $fluxo->delete();

        return redirect()->route('fluxo_caixa.index')
            ->with('success', 'Registro de fluxo excluído com sucesso!');
    }
}
