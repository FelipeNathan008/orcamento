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
use App\Models\SaldoConta;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class FluxoCaixaController extends Controller
{
    public function index(Request $request)
    {
        $dataInicio = $request->data_inicio;
        $dataFim = $request->data_fim;

        $query = FluxoCaixa::with([
            'tipo',
            'movimentacao',
            'conta'
        ]);

        if ($dataInicio && !$dataFim) {

            $query->whereDate(
                'flu_data_despesa',
                $dataInicio
            );
        }

        if ($dataInicio && $dataFim) {

            $query->whereBetween(
                'flu_data_despesa',
                [$dataInicio, $dataFim]
            );
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

            $mov = mb_strtolower(trim($fluxo->movimentacao->mov_nome ?? ''));

            if (str_contains($mov, 'entrada')) {
                $entrada += (float) $fluxo->flu_valor;
            }

            if (
                str_contains($mov, 'saida') ||
                str_contains($mov, 'saída')
            ) {
                $saida += (float) $fluxo->flu_valor;
            }
        }

        $caixa = Caixa::first();
        $saldoTotal = $caixa ? $caixa->caixa_saldo : 0;

        $contaSelecionada = $request->conta_bancaria_id;

        $contas = ContaBancaria::all();

        $dataFiltro = $data ?? now()->toDateString();

        $entrada = 0;
        $saida = 0;
        $saldoConta = 0;

        if ($contaSelecionada) {

            $fluxosDoDia = FluxoCaixa::with(['movimentacao'])
                ->whereDate('flu_data_despesa', $dataFiltro)
                ->where('conta_bancaria_id', $contaSelecionada)
                ->get();

            foreach ($fluxosDoDia as $fluxo) {

                $mov = mb_strtolower(
                    trim($fluxo->movimentacao->mov_nome ?? '')
                );

                if (str_contains($mov, 'entrada')) {
                    $entrada += (float) $fluxo->flu_valor;
                }

                if (
                    str_contains($mov, 'saida') ||
                    str_contains($mov, 'saída')
                ) {
                    $saida += (float) $fluxo->flu_valor;
                }
            }

            $saldoConta = SaldoConta::where(
                'id_conta_bancaria_id',
                $contaSelecionada
            )->value('saldo_conta_valor') ?? 0;
        }

        return view('view_fluxo_caixa.index', compact('fluxos', 'entrada', 'saida', 'saldoConta', 'contas', 'contaSelecionada', 'dataInicio', 'dataFim'));
    }


    public function gerarFluxoPdfPorData(Request $request)
    {
        $dataInicio = $request->data_inicio;
        $dataFim = $request->data_fim;

        $query = FluxoCaixa::with([
            'tipo',
            'movimentacao'
        ]);

        if ($dataInicio && !$dataFim) {

            $query->whereDate(
                'flu_data_despesa',
                $dataInicio
            );
        }

        if ($dataInicio && $dataFim) {

            $query->whereBetween(
                'flu_data_despesa',
                [$dataInicio, $dataFim]
            );
        }

        $fluxos = $query->get();

        $pdf = Pdf::loadView(
            'view_fluxo_caixa.pdf',
            compact(
                'fluxos',
                'dataInicio',
                'dataFim'
            )
        );

        $nomeArquivo = 'Fluxo_de_Caixa';

        if ($dataInicio && $dataFim) {

            $nomeArquivo .= '_' .
                \Carbon\Carbon::parse($dataInicio)->format('d-m-Y') .
                '_a_' .
                \Carbon\Carbon::parse($dataFim)->format('d-m-Y');
        } elseif ($dataInicio) {

            $nomeArquivo .= '_' .
                \Carbon\Carbon::parse($dataInicio)->format('d-m-Y');
        }

        return $pdf->download($nomeArquivo . '.pdf');
    }

    public function create()
    {
        $contas = ContaBancaria::all();
        $tipos = TipoFluxoCaixa::all();
        $movimentacoes = Movimentacao::all();

        return view(
            'view_fluxo_caixa.create',
            compact(
                'contas',
                'tipos',
                'movimentacoes'
            )
        );
    }

    public function store(Request $request)
    {
        $valor = str_replace('.', '', $request->flu_valor);
        $valor = str_replace(',', '.', $valor);
        $valor = (float) $valor;
        $request->validate([
            'flu_data_despesa'      => 'required|date',
            'conta_bancaria_id'     => 'required',
            'flu_id_tipo'           => 'required',
            'flu_id_movimentacao'   => 'required',
            'flu_valor'             => 'required|numeric|min:0.01',
            'flu_tipo_fiscal'       => 'required|max:100',
            'flu_num_doc'           => 'nullable|max:100',
            'flu_desc'              => 'required|max:180',
        ]);

        DB::beginTransaction();

        try {

            $movimentacao = Movimentacao::findOrFail(
                $request->flu_id_movimentacao
            );

            $saldoConta = SaldoConta::firstOrCreate(
                [
                    'id_conta_bancaria_id' => $request->conta_bancaria_id
                ],
                [
                    'saldo_conta_valor' => 0
                ]
            );

            $valor = (float) $request->flu_valor;

            $nomeMovimentacao = strtolower(
                trim($movimentacao->mov_nome)
            );

            if (str_contains($nomeMovimentacao, 'entrada')) {
                $saldoConta->saldo_conta_valor += $valor;
                $saldoConta->save();
            }


            if (
                str_contains($nomeMovimentacao, 'saida') ||
                str_contains($nomeMovimentacao, 'saída')
            ) {

                if ($saldoConta->saldo_conta_valor < $valor) {
                    DB::rollBack();
                    return back()
                        ->withInput()
                        ->with(
                            'error',
                            'Saldo insuficiente para realizar esta saída.'
                        );
                }

                $saldoConta->saldo_conta_valor -= $valor;

                $saldoConta->save();
            }

            FluxoCaixa::create([
                'flu_data_despesa'      => $request->flu_data_despesa,
                'conta_bancaria_id'     => $request->conta_bancaria_id,
                'flu_id_tipo'           => $request->flu_id_tipo,
                'flu_id_movimentacao'   => $request->flu_id_movimentacao,
                'flu_valor'             => $valor,
                'flu_tipo_fiscal'       => $request->flu_tipo_fiscal,
                'flu_num_doc'           => $request->flu_num_doc,
                'flu_desc'              => $request->flu_desc,
            ]);

            DB::commit();
            return redirect()
                ->route('fluxo_caixa.index')
                ->with('success', 'Lançamento criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Erro ao salvar lançamento: ' . $e->getMessage()
                );
        }
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
                'conta_bancaria_id' => $validatedData['conta_bancaria_id'],
                'flu_id_tipo' => $validatedData['flu_id_tipo'],
                'flu_id_movimentacao' => $validatedData['flu_id_movimentacao'],
                'flu_valor' => $validatedData['flu_valor'],
                'flu_tipo_fiscal' => $validatedData['flu_tipo_fiscal'],
                'flu_num_doc' => $validatedData['flu_num_doc'],
                'flu_desc' => $validatedData['flu_desc'],
            ]);

            $mov = Movimentacao::find($validatedData['flu_id_movimentacao']);

            $saldoConta = SaldoConta::firstOrCreate(
                [
                    'id_conta_bancaria_id' => $validatedData['conta_bancaria_id']
                ],
                [
                    'saldo_conta_valor' => 0
                ]
            );

            $nomeMov = strtolower(trim($mov->mov_nome));

            if (str_contains($nomeMov, 'saida')) {

                if (($saldoConta->saldo_conta_valor - $validatedData['flu_valor']) < 0) {
                    throw new \Exception('Saldo insuficiente na conta bancária.');
                }
            }

            if (str_contains($nomeMov, 'e   ntrada')) {

                $saldoConta->saldo_conta_valor += $validatedData['flu_valor'];
            } elseif (
                str_contains($nomeMov, 'saida') || str_contains($nomeMov, 'saída')
            ) {

                $saldoConta->saldo_conta_valor -= $validatedData['flu_valor'];
            }

            $saldoConta->save();

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

    public function destroy(string $id)
    {
        $fluxo = FluxoCaixa::findOrFail($id);

        $mov = Movimentacao::find($fluxo->flu_id_movimentacao);

        $saldoConta = SaldoConta::where(
            'id_conta_bancaria_id',
            $fluxo->conta_bancaria_id
        )->first();

        if ($saldoConta && $mov) {

            $nomeMov = strtolower(trim($mov->mov_nome));

            if (str_contains($nomeMov, 'entrada')) {

                if (($saldoConta->saldo_conta_valor - $fluxo->flu_valor) < 0) {

                    return redirect()->back()
                        ->with(
                            'error',
                            'Não é possível excluir. O saldo ficaria negativo.'
                        );
                }

                $saldoConta->saldo_conta_valor -= $fluxo->flu_valor;
            } elseif (
                str_contains($nomeMov, 'saida') || str_contains($nomeMov, 'saída')
            ) {

                $saldoConta->saldo_conta_valor += $fluxo->flu_valor;
            }

            $saldoConta->save();
        }

        $fluxo->delete();

        return redirect()
            ->route('fluxo_caixa.index')
            ->with('success', 'Registro excluído com sucesso!');
    }
}
