<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use App\Models\Customizacao;
use App\Models\DetalhesOrcamento;
use App\Models\PrecoCustomizacao;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;

class CustomizacaoController extends Controller
{
    private const SESSION_KEY = 'customizacao.index_url';

    private function urlIndex($detalhe): string
    {
        return session(
            self::SESSION_KEY,
            route('customizacao.index', CryptHelper::encrypt($detalhe->id_det))
        );
    }

    private function queryCustomizacoes(Request $request, int $detalheId)
    {
        $query = Customizacao::where(
            'detalhes_orcamento_id_det',
            $detalheId
        );

        if ($request->filled('tipo')) {
            $query->where('cust_tipo', $request->tipo);
        }

        if ($request->filled('local')) {
            $query->where('cust_local', $request->local);
        }

        if ($request->filled('posicao')) {
            $query->where('cust_posicao', $request->posicao);
        }

        if ($request->filled('formatacao')) {
            $query->where('cust_formatacao', $request->formatacao);
        }

        return $query;
    }

    public function index(Request $request, $id): View|RedirectResponse
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $detalheId = CryptHelper::decrypt($id);

        $detalhe = DetalhesOrcamento::with([
            'produto',
            'orcamento.clienteOrcamento',
            'customizacoes'
        ])->findOrFail($detalheId);

        $urlDetalhesOrcamento = session(
            'detalhes_orcamento.index_url',
            route(
                'detalhes_orcamento.index',
                CryptHelper::encrypt($detalhe->orcamento->id_orcamento)
            )
        );

        $customizacoes = $this->queryCustomizacoes($request, $detalheId)
            ->with([
                'detalhesOrcamento.produto',
                'detalhesOrcamento.orcamento.clienteOrcamento'
            ])
            ->orderBy('id_customizacao')
            ->get();

        $tipos = Customizacao::where('detalhes_orcamento_id_det', $detalheId)
            ->select('cust_tipo')
            ->distinct()
            ->orderBy('cust_tipo')
            ->pluck('cust_tipo');

        $locais = Customizacao::where('detalhes_orcamento_id_det', $detalheId)
            ->select('cust_local')
            ->distinct()
            ->orderBy('cust_local')
            ->pluck('cust_local');

        $posicoes = Customizacao::where('detalhes_orcamento_id_det', $detalheId)
            ->select('cust_posicao')
            ->distinct()
            ->orderBy('cust_posicao')
            ->pluck('cust_posicao');

        $formatacoes = Customizacao::where('detalhes_orcamento_id_det', $detalheId)
            ->select('cust_formatacao')
            ->distinct()
            ->orderBy('cust_formatacao')
            ->pluck('cust_formatacao');

        return view('view_customizacao.index', [
            'customizacoes' => $customizacoes,
            'detalhe' => $detalhe,
            'orcamento' => $detalhe->orcamento,
            'cliente' => $detalhe->orcamento->clienteOrcamento,
            'tipos' => $tipos,
            'locais' => $locais,
            'posicoes' => $posicoes,
            'formatacoes' => $formatacoes,
            'urlDetalhesOrcamento' => $urlDetalhesOrcamento,
        ]);
    }

    public function camisa(Request $request, $id)
    {
        $id = CryptHelper::decrypt($id);

        $customizacao = Customizacao::with([
            'detalhesOrcamento.produto',
            'detalhesOrcamento.orcamento.clienteOrcamento'
        ])->findOrFail($id);

        $detalheId = $customizacao->detalhes_orcamento_id_det;

        $urlVoltar = $request->filled('return_url')
            ? $request->input('return_url')
            : $this->urlIndex($customizacao->detalhesOrcamento);

        $urlVoltarLimpo = route('customizacao.index', [
            'id' => CryptHelper::encrypt($detalheId),
        ]);

        session([self::SESSION_KEY => $urlVoltar]);

        $allCustomizacoesForDetail = Customizacao::where(
            'detalhes_orcamento_id_det',
            $detalheId
        )->get();

        return view(
            'view_customizacao.camisa',
            compact(
                'customizacao',
                'allCustomizacoesForDetail',
                'urlVoltar',
                'urlVoltarLimpo'
            )
        );
    }

    public function create(Request $request, $id): View
    {
        $detalheId = CryptHelper::decrypt($id);

        $detalhe = DetalhesOrcamento::with([
            'produto',
            'orcamento.clienteOrcamento',
            'customizacoes'
        ])->findOrFail($detalheId);

        $urlVoltar = $request->filled('return_url')
            ? $request->input('return_url')
            : $this->urlIndex($detalhe);

        session([self::SESSION_KEY => $urlVoltar]);

        $precos = PrecoCustomizacao::all();
        $customizacoes = Customizacao::all();

        return view('view_customizacao.create', compact('detalhe', 'precos', 'customizacoes', 'urlVoltar'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'detalhes_orcamento_id_det' => 'required|exists:detalhes_orcamento,id_det',
                'cust_tipo' => 'required|string|max:45',
                'cust_local' => 'required|string|max:45',
                'cust_posicao' => 'required|string|max:45',
                'cust_tamanho' => 'required|string|max:45',
                'cust_formatacao' => 'required|string|max:45',
                'cust_valor' => 'required|string',
                'cust_descricao' => 'nullable|string|max:90',
                'cust_imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            ], [
                'cust_imagem.image' => 'O arquivo enviado deve ser uma imagem.',
                'cust_imagem.mimes' => 'A imagem deve estar no formato JPEG, PNG, JPG ou GIF.',
                'cust_imagem.max' => 'A imagem não pode ser maior que 5 MB.',
            ]);

            $customizacaoData = $validatedData;

            if (isset($customizacaoData['cust_valor'])) {
                $valorLimpo = str_replace(
                    ['.', ','],
                    ['', '.'],
                    $customizacaoData['cust_valor']
                );

                $customizacaoData['cust_valor'] = (float) $valorLimpo;
            }

            if ($request->hasFile('cust_imagem')) {
                $image = $request->file('cust_imagem');
                $nomeImagem = time() . '_' . $image->getClientOriginalName();

                $image->move(
                    public_path('images_customizacoes'),
                    $nomeImagem
                );

                $customizacaoData['cust_imagem'] = $nomeImagem;
            }

            $customizacao = Customizacao::create($customizacaoData);

            $detalhe = DetalhesOrcamento::findOrFail(
                $customizacao->detalhes_orcamento_id_det
            );

            $urlVoltar = $request->filled('return_url')
                ? $request->input('return_url')
                : $this->urlIndex($detalhe);

            session([self::SESSION_KEY => $urlVoltar]);

            return redirect($urlVoltar)
                ->with('success', 'Customização criada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error(
                'Erro ao criar customização: ' . $e->getMessage(),
                ['exception' => $e]
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Não foi possível criar a Customização: ' . $e->getMessage()
                )
                ->withInput();
        }
    }

    public function show(Request $request, $id)
    {
        $id = CryptHelper::decrypt($id);

        $customizacao = Customizacao::with([
            'detalhesOrcamento.orcamento.clienteOrcamento',
            'detalhesOrcamento.produto'
        ])->findOrFail($id);

        $detalhe = $customizacao->detalhesOrcamento;

        $urlVoltar = $request->filled('return_url')
            ? $request->input('return_url')
            : $this->urlIndex($detalhe);

        session([self::SESSION_KEY => $urlVoltar]);

        return view(
            'view_customizacao.show',
            compact('customizacao', 'urlVoltar')
        );
    }

    public function edit(Request $request, $id): View
    {
        $id = CryptHelper::decrypt($id);

        $customizacao = Customizacao::with([
            'detalhesOrcamento.produto',
            'detalhesOrcamento.orcamento.clienteOrcamento'
        ])->findOrFail($id);

        $detalhe = $customizacao->detalhesOrcamento;

        $urlVoltar = $request->filled('return_url')
            ? $request->input('return_url')
            : $this->urlIndex($detalhe);

        session([self::SESSION_KEY => $urlVoltar]);

        $precos = PrecoCustomizacao::all();

        $customizacoes = Customizacao::where(
            'detalhes_orcamento_id_det',
            $detalhe->id_det
        )->get();

        return view(
            'view_customizacao.edit',
            compact(
                'customizacao',
                'detalhe',
                'precos',
                'customizacoes',
                'urlVoltar'
            )
        );
    }

    public function update(Request $request, $id)
    {
        $id = CryptHelper::decrypt($id);

        try {
            $customizacao = Customizacao::findOrFail($id);

            $validatedData = $request->validate([
                'detalhes_orcamento_id_det' => 'sometimes|required|exists:detalhes_orcamento,id_det',
                'cust_tipo' => 'sometimes|required|string|max:45',
                'cust_local' => 'sometimes|required|string|max:45',
                'cust_posicao' => 'sometimes|required|string|max:45',
                'cust_tamanho' => 'sometimes|required|string|max:45',
                'cust_formatacao' => 'sometimes|required|string|max:45',
                'cust_valor' => 'sometimes|required|string',
                'cust_descricao' => 'nullable|string|max:90',

                'cust_imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            ], [
                'cust_imagem.image' => 'O arquivo enviado deve ser uma imagem.',
                'cust_imagem.mimes' => 'A imagem deve estar no formato JPEG, PNG, JPG ou GIF.',
                'cust_imagem.max' => 'A imagem não pode ser maior que 5 MB.',
            ]);

            $customizacaoData = $validatedData;

            if (isset($customizacaoData['cust_valor'])) {
                $valorLimpo = str_replace(
                    ['.', ','],
                    ['', '.'],
                    $customizacaoData['cust_valor']
                );

                $customizacaoData['cust_valor'] = (float) $valorLimpo;
            }

            if ($request->hasFile('cust_imagem')) {
                $image = $request->file('cust_imagem');

                if ($customizacao->cust_imagem) {
                    $caminhoAntigo = public_path(
                        'images_customizacoes/' . $customizacao->cust_imagem
                    );

                    if (File::exists($caminhoAntigo)) {
                        File::delete($caminhoAntigo);
                    }
                }

                $nomeImagem = time() . '_' . $image->getClientOriginalName();

                $image->move(
                    public_path('images_customizacoes'),
                    $nomeImagem
                );

                $customizacaoData['cust_imagem'] = $nomeImagem;
            } else {
                unset($customizacaoData['cust_imagem']);
            }

            $customizacao->update($customizacaoData);

            $detalhe = DetalhesOrcamento::findOrFail(
                $customizacao->detalhes_orcamento_id_det
            );

            $urlVoltar = $request->filled('return_url')
                ? $request->input('return_url')
                : $this->urlIndex($detalhe);

            session([self::SESSION_KEY => $urlVoltar]);

            return redirect($urlVoltar)
                ->with('success', 'Customização atualizada com sucesso!');
        } catch (ValidationException $e) {
            Log::error(
                'Erro de validação ao atualizar customização: ' . $e->getMessage(),
                ['errors' => $e->errors()]
            );

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error(
                'Erro inesperado ao atualizar customização: ' . $e->getMessage(),
                ['exception' => $e]
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Não foi possível atualizar a Customização: ' . $e->getMessage()
                )
                ->withInput();
        }
    }

    public function destroy(Request $request, $id)
    {
        $id = CryptHelper::decrypt($id);

        try {
            $customizacao = Customizacao::findOrFail($id);
            $idDet = $customizacao->detalhes_orcamento_id_det;

            if ($customizacao->cust_imagem) {
                $caminhoImagem = public_path(
                    'images_customizacoes/' . $customizacao->cust_imagem
                );

                if (File::exists($caminhoImagem)) {
                    File::delete($caminhoImagem);
                }
            }

            $customizacao->delete();

            $urlVoltar = $this->urlIndex(
                DetalhesOrcamento::findOrFail($idDet)
            );

            session([self::SESSION_KEY => $urlVoltar]);

            return redirect($urlVoltar)
                ->with('success', 'Customização excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error(
                'Erro ao excluir customização: ' . $e->getMessage(),
                ['exception' => $e]
            );

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Não foi possível excluir a Customização: ' . $e->getMessage()
                );
        }
    }
}
