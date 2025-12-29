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

    /**
     * Exibir registro específico
     */
    public function show($id)
    {
        $detalhe = DetalhesCobranca::findOrFail($id);
        return view('view_detalhes_cobranca.show', compact('detalhe'));
    }

    /**
     * Form de edição
     */
    public function edit($id)
    {
        $detalhe = DetalhesCobranca::findOrFail($id);
        $jurosMulta = JurosMulta::first();
        return view('view_detalhes_cobranca.edit', compact('detalhe', 'jurosMulta'));
    }

    public function update(Request $request, $id)
    {
        // Busca o detalhe da cobrança
        $detalhe = DetalhesCobranca::findOrFail($id);

        // Atualiza DETALHES_COBRANCA
        $detalhe->update([
            'det_cobr_valor_parcela' => $request->det_cobr_valor_parcela,
            'det_cobr_data_venc'     => $request->det_cobr_data_venc,
            'det_cobr_status'        => 'Acordo',
        ]);

        // Atualiza DETALHES_FORMA_PAG
        if ($detalhe->id_det_forma) {
            DetalhesFormaPag::where('id_det_forma', $detalhe->id_det_forma)
                ->update([
                    'det_forma_valor_parcela' => $request->det_cobr_valor_parcela,
                    'det_forma_data_venc'     => $request->det_cobr_data_venc,
                    'det_situacao'            => 'Acordo',
                ]);
        }

        return redirect()
            ->route('cobranca.index')
            ->with('success', 'Acordo realizado com sucesso!');
    }



    /**
     * Deletar registro
     */
    public function destroy($id)
    {
        $detalhe = DetalhesCobranca::findOrFail($id);
        $detalhe->delete();

        return redirect()->route('view_detalhes_cobranca.index')
            ->with('success', 'Detalhe excluído com sucesso!');
    }
}
