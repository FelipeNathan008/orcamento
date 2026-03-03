<?php

use App\Http\Controllers\ProfileController;
use App\Models\PrecoCustomizacao;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ClienteOrcamentoController;
use App\Http\Controllers\ContatoClienteController;
use App\Http\Controllers\OrcamentoController;
use App\Http\Controllers\DetalhesOrcamentoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CustomizacaoController;
use App\Http\Controllers\PrecoCustomizacaoController;
use App\Models\Customizacao;
use App\Http\Controllers\ContactController; // Importe o ContactController
use App\Models\Preco; // Importe o modelo Preco para usar na nova rota
use Illuminate\Http\Request; // Importe a classe Request
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TipoPagamentoController;
use App\Http\Controllers\FormaPagamentoController;
use App\Http\Controllers\DetalhesFormaPagController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\LogStatusController;
use App\Http\Controllers\StatusMercadoriaController;
use App\Http\Controllers\CobrancaController;
use App\Http\Controllers\DetalhesCobrancaController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/financeiro', function () {
    return view('financeiro');
})->middleware(['auth', 'verified'])->name('financeiro');

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');


// ROTAS PROTEGIDAS PARA ADMIN (apenas admin pode gerenciar usuários e financeiro)
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //ROUTE USERS
    Route::resource('users', UserController::class);

    //ROUTE FINANCEIRO E PAGAMENTO
    Route::resource('tipo_pagamento', TipoPagamentoController::class);
    Route::resource('detalhes_forma_pag', DetalhesFormaPagController::class);
    Route::resource('forma_pagamento', FormaPagamentoController::class);
    Route::get('/detalhes_forma_pag/create', [DetalhesFormaPagController::class, 'create'])
        ->name('detalhes_forma_pag.create');

    Route::resource('financeiro', FinanceiroController::class);
    Route::resource('log_status', LogStatusController::class);
    Route::resource('status_mercadoria', StatusMercadoriaController::class);
    Route::post('/financeiro/{id}/prosseguir', [FinanceiroController::class, 'prosseguir'])
        ->name('financeiro.prosseguir');
    Route::post('/parcelas/{id}/dar-baixa', [FormaPagamentoController::class, 'darBaixa'])
        ->name('parcelas.darBaixa');
        Route::post('/parcelas/{id}/voltar-nao-pago', [FormaPagamentoController::class, 'voltarNaoPago'])
    ->name('parcelas.voltarNaoPago');
    Route::resource('notificacao', NotificacaoController::class);



    Route::resource('cobranca', CobrancaController::class);
    Route::resource('detalhes_cobranca', DetalhesCobrancaController::class);
});

// ROTAS PROTEGIDAS PARA USUÁRIOS AUTENTICADOS (user e admin)
Route::middleware(['auth', 'role:user|admin'])->group(function () {

    // ROUTES CLIENTE
    Route::resource('cliente', ClienteController::class);

    // ROUTES EMPRESA
    Route::resource('empresa', EmpresaController::class);

    // ROTAS CLIENTE ORCAMENTO
    Route::resource('cliente_orcamento', ClienteOrcamentoController::class);

    // ROTAS CONTATO CLIENTE
    Route::get('/contato_cliente/create/{cliente_orcamento}', [ContatoClienteController::class, 'create'])->name('contato_cliente.create_for_cliente');
    Route::resource('contato_cliente', ContatoClienteController::class);

    // ROTAS ORÇAMENTO
    Route::resource('orcamento', OrcamentoController::class);


    // ROTAS PRODUTO
    Route::get('/produtos/{id}/details', [ProdutoController::class, 'getProdutoDetails'])->name('produto.details');
    Route::resource('produto', ProdutoController::class);

    // ROUTES DETALHES ORCAMENTO
    Route::get('/detalhes_orcamento/create/{orcamento_id?}', [DetalhesOrcamentoController::class, 'create'])->name('detalhes_orcamento.create');
    Route::resource('detalhes_orcamento', DetalhesOrcamentoController::class);

    // ROTAS CUSTOMIZAÇÃO
    Route::resource('customizacao', CustomizacaoController::class);

    // --- ROTAS API ---

    // ROTA API PARA CUSTOMIZAÇÕES POR DETALHE (já existia)
    Route::get('/api/customizacoes-por-detalhe/{detalheId}', [CustomizacaoController::class, 'getCustomizacoesPorDetalhe']);

    // ROTA API ADICIONADA PARA PEGAR OS TAMANHOS POR TIPO DE PREÇO
    // A rota aceita um parâmetro 'tipo' na query string (e.g., /api/tamanhos-por-tipo?tipo=Bordado Padrão)
    Route::get('/api/tamanhos-por-tipo', function (Request $request) {
        $tipo = $request->input('tipo');

        if (!$tipo) {
            return response()->json([], 400); // Bad Request
        }

        $tamanhos = PrecoCustomizacao::where('preco_tipo', $tipo)
            ->pluck('preco_tamanho') // pega só os tamanhos
            ->map(function ($t) {
                // Remove espaços extras e normaliza para evitar duplicatas
                return preg_replace('/\s+/u', ' ', trim($t));
            })
            ->unique(function ($t) {
                // Garante que seja case-insensitive
                return mb_strtolower($t);
            })
            ->values() // reindexa
            ->toArray();

        return response()->json($tamanhos);
    });


    // --- FIM ROTAS API ---

    // ROTA PARA EXIBIR IMAGEM
    Route::get('/imagem/{filename}', function ($filename) {
        $path = storage_path('app/public/imagens/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    })->name('imagem.mostrar');

    // ROTA CORRIGIDA PARA O LAYOUT DA CAMISA (camisa.blade.php)
    Route::get('/camisa/{id}', function ($id) {
        $customizacao = Customizacao::findOrFail($id);
        return view('view_customizacao.camisa', compact('customizacao'));
    })->name('camisa.show_layout');

    // ROTA ADICIONADA PARA GERAR O ORÇAMENTO
    Route::get('/orcamento/gerar/{id}', [OrcamentoController::class, 'gerarOrcamento'])->name('gerar_orcamento');
    Route::get('/orcamento/pdf/{id}', [OrcamentoController::class, 'gerarOrcamentoPDF'])->name('gerar_orcamento_pdf');
    // web.php
    Route::get('/orcamento/preview/{id}', [OrcamentoController::class, 'previewOrcamento'])->name('orcamento_preview');


    // ROTA PARA PREÇO CUSTOMIZAÇÃO
    Route::resource('preco_customizacao', PrecoCustomizacaoController::class);
});

require __DIR__ . '/auth.php';
