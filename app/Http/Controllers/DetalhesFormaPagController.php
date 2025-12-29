<?php

namespace App\Http\Controllers;

use App\Models\DetalhesFormaPag;
use App\Models\FormaPagamento;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DetalhesFormaPagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Pega o primeiro parâmetro sem nome → ID da forma de pagamento
        $id_forma_pag = array_key_first($request->query());

        // Pega o ID do financeiro (parâmetro normal)
        $id_financeiro = $request->query('fin');

        // Se não tiver o id da forma de pagamento, redireciona
        if (!$id_forma_pag || !is_numeric($id_forma_pag)) {
            return redirect('/financeiro');
        }

        // Busca os detalhes da forma de pagamento
        $detalhes = DetalhesFormaPag::where('id_forma_pag', $id_forma_pag)->get();

        // Envia também o id do financeiro para a view
        return view('view_detalhes_forma_pag.index', compact('detalhes', 'id_financeiro'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $formas = FormaPagamento::all();
        return view('view_detalhes_forma_pag.create', compact('formas'));
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        try {

            // Validação do primeiro campo
            $validated = $request->validate([
                'id_forma_pag' => 'required|exists:forma_pagamento,id_forma_pag',
                'datas_parcelas' => 'required|array|min:1',
                'valores_parcelas' => 'required|array|min:1',
            ]);

            $idForma = $request->id_forma_pag;
            $datas = $request->datas_parcelas;
            $valores = $request->valores_parcelas;

            // Salvar cada parcela separadamente
            foreach ($datas as $index => $data) {
                DetalhesFormaPag::create([
                    'id_forma_pag' => $idForma,
                    'det_forma_data_venc' => $data,
                    'det_forma_valor_parcela' => $valores[$index],
                ]);
            }

            return redirect()
                ->route('detalhes_forma_pag.index')
                ->with('success', 'Parcelas cadastradas com sucesso!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao criar parcelas: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function show($id)
    {
        $detalhe = DetalhesFormaPag::findOrFail($id);
        return view('view_detalhes_forma_pag.show', compact('detalhe'));
    }
    public function edit($id)
    {
        abort(404); // bloquear
    }

    public function update(Request $request, $id)
    {
        abort(404); // bloquear
    }

    public function destroy($id)
    {
        abort(404); // bloquear
    }
}
