<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Customizacao;
use App\Models\PrecoCustomizacao;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ClienteOrcamentoController;
use App\Http\Controllers\ContatoClienteController;
use App\Http\Controllers\OrcamentoController;
use App\Http\Controllers\DetalhesOrcamentoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CustomizacaoController;
use App\Http\Controllers\PrecoCustomizacaoController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\FluxoCaixaController;
use App\Http\Controllers\TipoFluxoCaixaController;
use App\Http\Controllers\ContaBancariaController;
use App\Http\Controllers\SaldoContaBancariaController;
use App\Http\Controllers\TipoPagamentoController;
use App\Http\Controllers\FormaPagamentoController;
use App\Http\Controllers\CobrancaController;
use App\Http\Controllers\DetalhesCobrancaController;
use App\Http\Controllers\LogStatusController;
use App\Http\Controllers\StatusMercadoriaController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\NotaFiscalController;


// ROTAS PÚBLICAS
Route::get('/', function () {
    return view('welcome');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');


// DASHBOARD E FINANCEIRO
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/financeiro', function () {
    return view('financeiro');
})->middleware(['auth', 'verified'])->name('financeiro');


// ROTAS ADMIN

Route::middleware(['auth', 'role:admin'])->group(function () {

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    //USERS
    Route::resource('users', UserController::class);

    // ADMINISTRACAO
    Route::get('/administracao', function () {
        return view('administracao.index');
    })->name('administracao.index');


    // PAGAMENTOS
    Route::resource('tipo_pagamento', TipoPagamentoController::class);
    Route::resource('forma_pagamento', FormaPagamentoController::class);

    // FINANCEIRO
    Route::resource('financeiro', FinanceiroController::class);

    Route::post('/financeiro/{id}/prosseguir', [FinanceiroController::class, 'prosseguir'])
        ->name('financeiro.prosseguir');

    // PARCELAS
    Route::post('/parcelas/{id}/dar-baixa', [FormaPagamentoController::class, 'darBaixa'])
        ->name('parcelas.darBaixa');

    Route::post('/parcelas/{id}/voltar-nao-pago', [FormaPagamentoController::class, 'voltarNaoPago'])
        ->name('parcelas.voltarNaoPago');

    //STATUS E NOTIFICAÇÕES
    Route::resource('log_status', LogStatusController::class);
    Route::resource('status_mercadoria', StatusMercadoriaController::class);
    Route::resource('notificacao', NotificacaoController::class);
    //COBRANCAS

    Route::resource('cobranca', CobrancaController::class);
    Route::resource('detalhes_cobranca', DetalhesCobrancaController::class);
});

Route::middleware(['auth', 'role:user|admin'])->group(function () {

    //CLIENTES E ORCAMENTO
    Route::resource('cliente', ClienteController::class);
    Route::resource('empresa', EmpresaController::class);
    Route::resource('cliente_orcamento', ClienteOrcamentoController::class);

    Route::get(
        '/contato_cliente/create/{cliente_orcamento}',
        [ContatoClienteController::class, 'create']
    )->name('contato_cliente.create_for_cliente');

    Route::resource('contato_cliente', ContatoClienteController::class);

    Route::resource('orcamento', OrcamentoController::class);

    Route::get('/orcamento/gerar/{id}', [OrcamentoController::class, 'gerarOrcamento'])
        ->name('gerar_orcamento');
    Route::get('/orcamento/pdf/{id}', [OrcamentoController::class, 'gerarOrcamentoPDF'])
        ->name('gerar_orcamento_pdf');
    Route::get('/orcamento/preview/{id}', [OrcamentoController::class, 'previewOrcamento'])
        ->name('orcamento_preview');

    Route::get(
        '/detalhes_orcamento/create/{orcamento_id?}',
        [DetalhesOrcamentoController::class, 'create']
    )->name('detalhes_orcamento.create');

    Route::resource('detalhes_orcamento', DetalhesOrcamentoController::class);

    // PRODUTOS

    Route::get('/produtos/{id}/details', [ProdutoController::class, 'getProdutoDetails'])
        ->name('produto.details');

    Route::resource('produto', ProdutoController::class);

    // CUSTOMIZAÇÕES

    Route::resource('customizacao', CustomizacaoController::class);

    Route::get('/customizacao/{id}/layout', [CustomizacaoController::class, 'camisa'])
        ->name('customizacao.camisa');

    Route::resource('preco_customizacao', PrecoCustomizacaoController::class);


    // API - TAMANHOS POR TIPO DE CUSTOMIZAÇÃO

    Route::get('/api/tamanhos-por-tipo', function (Request $request) {

        $tipo = $request->input('tipo');

        if (!$tipo) {
            return response()->json([], 400);
        }

        $tamanhos = PrecoCustomizacao::where('preco_tipo', $tipo)
            ->pluck('preco_tamanho')
            ->map(function ($t) {
                return preg_replace('/\s+/u', ' ', trim($t));
            })
            ->unique(function ($t) {
                return mb_strtolower($t);
            })
            ->values()
            ->toArray();

        return response()->json($tamanhos);
    });

    Route::get('/imagem/{filename}', function ($filename) {

        $path = storage_path('app/public/imagens/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    })->name('imagem.mostrar');


    Route::get('/camisa/{id}', function ($id) {

        $customizacao = Customizacao::findOrFail($id);

        return view('view_customizacao.camisa', compact('customizacao'));
    })->name('camisa.show_layout');


    // FLUXO DE CAIXA, NOTA FISCAL E CONTA BANCÁRIA

    Route::resource('tipo_fluxo_caixa', TipoFluxoCaixaController::class);

    Route::get('/fluxo-caixa/pdf', [FluxoCaixaController::class, 'gerarFluxoPdfPorData']);

    Route::post('/fluxo-caixa/store-fluxo', [FluxoCaixaController::class, 'storeFluxo'])->name('fluxo_caixa.storeFluxo');

    Route::resource('fluxo_caixa', FluxoCaixaController::class);

    Route::resource('nota_fiscal', NotaFiscalController::class);

    Route::resource('conta_bancaria', ContaBancariaController::class);

    Route::post('/conta_bancaria/{id}/saldo', [ContaBancariaController::class, 'adicionarSaldo'])->name('conta_bancaria.adicionarSaldo');

    Route::get('/fluxo_nota_conta', function () {
        return view('fluxo_nota_conta');
    })->name('fluxo_nota_conta.index');
});


require __DIR__ . '/auth.php';
