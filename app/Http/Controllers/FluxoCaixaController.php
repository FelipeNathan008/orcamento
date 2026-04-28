<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FluxoCaixa;
use App\Models\TipoFluxoCaixa;
use App\Models\Movimentacao;
use App\Models\Financeiro;
use App\Models\LogStatus;
use App\Models\StatusMercadoria;
use App\Models\Caixa;
use App\Models\ContaBancaria;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class FluxoCaixaController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->data;

        $query = FluxoCaixa::with(['tipo', 'movimentacao', 'conta']);
        if ($data) {
            $query->whereDate('flu_data_despesa', $data);
        }

        $fluxos = $query->get();

        // PEGA SOMENTE HOJE (ou data selecionada)
        $dataFiltro = $data ?? now()->toDateString();

        $fluxosDoDia = FluxoCaixa::with(['tipo', 'movimentacao', 'conta'])
        ->whereDate('flu_data_despesa', $dataFiltro)
            ->get();

        // CALCULOS
        $entrada = 0;
        $saida = 0;

        foreach ($fluxosDoDia as $fluxo) {
            $nome = trim(strtolower($fluxo->movimentacao->mov_nome ?? ''));

            if ($nome == 'entrada caixa') {
                $entrada += $fluxo->flu_valor;
            }

            if ($nome == 'saida caixa' || $nome == 'saída caixa') {
                $saida += $fluxo->flu_valor;
            }
        }

        $caixa = Caixa::first();
        $saldoTotal = $caixa ? $caixa->caixa_saldo : 0;
        $saldo = $entrada - $saida;

        return view('view_fluxo_caixa.index', compact('fluxos', 'entrada', 'saida', 'saldoTotal', 'saldo', 'data'));
    }

    public function create()
    {
        $tipos = TipoFluxoCaixa::all();
        $caixa = Caixa::first();
        $contas = ContaBancaria::all();
        $saldoTotal = $caixa ? $caixa->caixa_saldo : 0;
        $movimentacoes = Movimentacao::all();

        return view('view_fluxo_caixa.create', compact('tipos', 'contas', 'movimentacoes', 'saldoTotal'));
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
            'conta_bancaria_id' => 'required|integer|exists:conta_bancaria,id_conta',
            'flu_valor' => 'required|numeric',
            'flu_tipo_fiscal' => 'required|string|max:90',
            'flu_num_doc' => 'nullable|string|max:255',
            'flu_desc' => 'required|string|max:180',
        ]);

        $mov = Movimentacao::find($validatedData['flu_id_movimentacao']);
        $caixa = Caixa::first();
        $saldoAtual = $caixa ? $caixa->caixa_saldo : 0;

        if ($mov && in_array(strtolower($mov->mov_nome), ['saida caixa', 'saída caixa'])) {

            if (($saldoAtual - $validatedData['flu_valor']) < 0) {
                return redirect()->back()
                    ->with('error', 'Saldo insuficiente no caixa!')
                    ->withInput();
            }
        }

        FluxoCaixa::create($validatedData);

        // CAIXA
        $mov = Movimentacao::find($validatedData['flu_id_movimentacao']);

        if ($mov && in_array(strtolower($mov->mov_nome), ['entrada caixa', 'saída caixa'])) {

            $caixa = Caixa::first();

            if (!$caixa) {
                $caixa = Caixa::create(['caixa_saldo' => 0]);
            }

            if (strtolower($mov->mov_nome) == 'entrada caixa') {
                $caixa->caixa_saldo += $validatedData['flu_valor'];
            } else {
                $caixa->caixa_saldo -= $validatedData['flu_valor'];
            }

            $caixa->save();
        }

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
                'conta_bancaria_id' => 'required|integer|exists:conta_bancaria,id_conta',
                'flu_valor' => 'required|numeric',
                'flu_tipo_fiscal' => 'required|string|max:90',
                'flu_num_doc' => 'nullable|string|max:255',
                'flu_desc' => 'required|string|max:180',

                'id_financeiro' => 'required|integer|exists:financeiro,id_fin'
            ]);

            FluxoCaixa::create([
                'flu_data_despesa' => $validatedData['flu_data_despesa'],
                'flu_id_tipo' => $validatedData['flu_id_tipo'],
                'flu_id_movimentacao' => $validatedData['flu_id_movimentacao'],
                'conta_bancaria_id' => $validatedData['conta_bancaria_id'],
                'flu_valor' => $validatedData['flu_valor'],
                'flu_tipo_fiscal' => $validatedData['flu_tipo_fiscal'],
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
        $fluxo = FluxoCaixa::with(['tipo', 'movimentacao', 'conta'])->findOrFail($id);
        return view('view_fluxo_caixa.show', compact('fluxo'));
    }

    public function edit(string $id)
    {
        $fluxo = FluxoCaixa::findOrFail($id);
        $tipos = TipoFluxoCaixa::all();
        $movimentacoes = Movimentacao::all();
        $caixa = Caixa::first();
        $saldoTotal = $caixa ? $caixa->caixa_saldo : 0;
        $contas = ContaBancaria::all();

        return view('view_fluxo_caixa.edit', compact('saldoTotal', 'fluxo', 'tipos', 'movimentacoes','contas'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'flu_data_despesa' => 'required|date',
            'flu_id_tipo' => 'required|integer',
            'flu_id_movimentacao' => 'required|integer',
            'conta_bancaria_id' => 'required|integer|exists:conta_bancaria,id_conta',
            'flu_valor' => 'required|numeric',
            'flu_tipo_fiscal' => 'required|string|max:90',
            'flu_num_doc' => 'nullable|string|max:255',
            'flu_desc' => 'required|string|max:180',
        ]);

        $fluxo = FluxoCaixa::findOrFail($id);

        // DADOS ANTIGOS
        $valorAntigo = $fluxo->flu_valor;
        $movAntigo = Movimentacao::find($fluxo->flu_id_movimentacao);

        // DADOS NOVOS
        $movNovo = Movimentacao::find($validatedData['flu_id_movimentacao']);
        $valorNovo = $validatedData['flu_valor'];

        $caixa = Caixa::first();

        if (!$caixa) {
            $caixa = Caixa::create(['caixa_saldo' => 0]);
        }

        // VALIDAÇÃO DE SALDO (ANTES DE ATUALIZAR)
        if ($movNovo && in_array(strtolower($movNovo->mov_nome), ['saida caixa', 'saída caixa'])) {

            $saldoSimulado = $caixa->caixa_saldo;

            // REMOVE EFEITO ANTIGO
            if ($movAntigo && strtolower($movAntigo->mov_nome) == 'entrada caixa') {
                $saldoSimulado -= $valorAntigo;
            } elseif ($movAntigo && in_array(strtolower($movAntigo->mov_nome), ['saida caixa', 'saída caixa'])) {
                $saldoSimulado += $valorAntigo;
            }

            // APLICA NOVO
            $saldoSimulado -= $valorNovo;

            if ($saldoSimulado < 0) {
                return redirect()->back()
                    ->with('error', 'Essa alteração deixaria o caixa negativo!')
                    ->withInput();
            }
        }

        // ATUALIZA FLUXO
        $fluxo->update($validatedData);

        // REMOVE EFEITO ANTIGO
        if ($movAntigo && in_array(strtolower($movAntigo->mov_nome), ['entrada caixa', 'saída caixa'])) {

            if (strtolower($movAntigo->mov_nome) == 'entrada caixa') {
                $caixa->caixa_saldo -= $valorAntigo;
            } else {
                $caixa->caixa_saldo += $valorAntigo;
            }
        }

        // APLICA NOVO
        if ($movNovo && in_array(strtolower($movNovo->mov_nome), ['entrada caixa', 'saída caixa'])) {

            if (strtolower($movNovo->mov_nome) == 'entrada caixa') {
                $caixa->caixa_saldo += $valorNovo;
            } else {
                $caixa->caixa_saldo -= $valorNovo;
            }
        }

        $caixa->save();

        return redirect()->route('fluxo_caixa.index')
            ->with('success', 'Registro de fluxo atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $fluxo = FluxoCaixa::findOrFail($id);

        $mov = Movimentacao::find($fluxo->flu_id_movimentacao);
        $caixa = Caixa::first();

        if (!$caixa) {
            $caixa = Caixa::create(['caixa_saldo' => 0]);
        }

        // SIMULAÇÃO DE SALDO
        $saldoSimulado = $caixa->caixa_saldo;

        if ($mov && in_array(strtolower($mov->mov_nome), ['entrada caixa', 'saída caixa'])) {

            if (strtolower($mov->mov_nome) == 'entrada caixa') {
                // remover entrada diminui saldo
                $saldoSimulado -= $fluxo->flu_valor;
            } else {
                // remover saída aumenta saldo
                $saldoSimulado += $fluxo->flu_valor;
            }

            // BLOQUEIO
            if ($saldoSimulado < 0) {
                return redirect()->back()
                    ->with('error', 'Não é possível excluir! Isso deixaria o caixa negativo.');
            }
        }

        // APLICA NO CAIXA REAL
        if ($mov && in_array(strtolower($mov->mov_nome), ['entrada caixa', 'saída caixa'])) {

            if (strtolower($mov->mov_nome) == 'entrada caixa') {
                $caixa->caixa_saldo -= $fluxo->flu_valor;
            } else {
                $caixa->caixa_saldo += $fluxo->flu_valor;
            }

            $caixa->save();
        }

        $fluxo->delete();

        return redirect()->route('fluxo_caixa.index')
            ->with('success', 'Registro de fluxo excluído com sucesso!');
    }
}
