<?php

namespace App\Http\Controllers;

use App\Models\DetalhesCobranca;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\JurosMulta;
use App\Models\DetalhesFormaPag;

class DetalhesCobrancaController extends Controller
{
    /**
     * Listagem geral
     */
    public function index()
    {
        abort(404); // bloquear
    }

    /**
     * Form de criação
     */
    public function create()
    {
        return view('view_detalhes_cobranca.create');
    }

    /**
     * Registrar no banco
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'cobranca_id'             => 'required|integer',
                'det_cobr_valor_parcela'  => 'required|string|max:45',
                'det_cobr_data_venc'      => 'required|date',
            ]);

            DetalhesCobranca::create($validated);

            return redirect()->route('view_detalhes_cobranca.index')
                ->with('success', 'Detalhe da cobrança criado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao criar detalhe: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $detalhe = DetalhesCobranca::findOrFail($id);
        return view('view_detalhes_cobranca.show', compact('detalhe'));
    }


    public function edit($id)
    {
        $detalhe = DetalhesCobranca::findOrFail($id);
        $jurosMulta = JurosMulta::first();
        return view('view_detalhes_cobranca.edit', compact('detalhe', 'jurosMulta'));
    }


    public function update(Request $request, $id)
    {
        $detalhe = DetalhesCobranca::findOrFail($id);
        $valorOriginal = (float) $detalhe->det_cobr_valor_parcela;
        $dataOriginal = \Carbon\Carbon::parse($detalhe->det_cobr_data_venc);
        $novaData = \Carbon\Carbon::parse($request->det_cobr_data_venc);
        $diasAtraso = max(0, $dataOriginal->diffInDays($novaData, false));

        $jurosMulta = JurosMulta::first();
        $indiceMulta = (float) ($jurosMulta->indice_multa ?? 2);
        $indiceJuros = (float) ($jurosMulta->indice_juros ?? 1);

        $multaCalculada = $valorOriginal * ($indiceMulta / 100);
        $jurosCalculado = $valorOriginal * ($indiceJuros / 100 / 30) * $diasAtraso;

        $descontoMulta = min($multaCalculada, max(0, (float) ($request->desconto_multa ?? 0)));
        $descontoJuros = min($jurosCalculado, max(0, (float) ($request->desconto_juros ?? 0)));

        $multaFinal = max(0, $multaCalculada - $descontoMulta);
        $jurosFinal = max(0, $jurosCalculado - $descontoJuros);
        $valorFinal = $valorOriginal + $multaFinal + $jurosFinal;

        $detalhe->update([
            'det_cobr_valor_parcela' => $valorFinal,
            'det_cobr_data_venc' => $request->det_cobr_data_venc,
            'det_cobr_status' => 'Acordo',
        ]);

        if ($detalhe->id_det_forma) {
            DetalhesFormaPag::where('id_det_forma', $detalhe->id_det_forma)->update([
                'det_forma_valor_parcela' => $valorFinal,
                'det_forma_data_venc' => $request->det_cobr_data_venc,
                'det_situacao' => 'Acordo',
            ]);
        }

        return redirect()->route('cobranca.index')->with('success', 'Acordo realizado com sucesso!');
    }



    public function destroy($id)
    {
        $detalhe = DetalhesCobranca::findOrFail($id);
        $detalhe->delete();

        return redirect()->route('view_detalhes_cobranca.index')
            ->with('success', 'Detalhe excluído com sucesso!');
    }
}
