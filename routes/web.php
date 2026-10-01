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
use App\Http\Controllers\LogStatusController;
use App\Http\Controllers\StatusMercadoriaController;
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\NotaFiscalController;
use App\Http\Controllers\OrcamentoFracionadoController;
use App\Http\Controllers\DetalhesOrcamentoFracionadoController;
use App\Http\Controllers\CustomizacaoFracionadaController;
use App\Http\Controllers\DashboardFinanceiroController;


// ROTAS PÚBLICAS
Route::get('/', function () {
    return view('welcome');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


Route::middleware(['auth', 'role:user|admin'])->group(function () {

    /*
         MÓDULO PRINCIPAL
    */

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //USERS
    Route::resource('users', UserController::class);

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/orcamentos', [DashboardController::class, 'orcamentos'])->name('dashboard.orcamentos');
    Route::get('/dashboard/orcamentos-fracionados', [DashboardController::class, 'orcamentosFracionados'])->name('dashboard.orcamentos.fracionados');
    Route::get('/dashboard/parcelas-financeiro', [DashboardFinanceiroController::class, 'parcelas'])->name('dashboard.parcelas.financeiro');
    Route::get('/dashboard/mapa-financeiro', [DashboardFinanceiroController::class, 'mapaFinanceiro'])->name('dashboard.mapa.financeiro');
    Route::get('/dashboard/mapa-financeiro/parcelas', [DashboardFinanceiroController::class, 'parcelasDia'])->name('dashboard.mapa.financeiro.parcelas');

    //EMPRESA E CLIENTE
    Route::get('/empresa_prospeccao', function () {
        return view('empresa_prospeccao');
    })->name('empresa_prospeccao.index');
    Route::resource('cliente', ClienteController::class);
    Route::resource('empresa', EmpresaController::class);

    // CLIENTES E ORÇAMENTO
    Route::resource('cliente_orcamento', ClienteOrcamentoController::class);
    Route::get('cliente_orcamento/create/{cliente?}', [ClienteOrcamentoController::class, 'create'])
        ->name('cliente_orcamento.create');

    // CONTATOS DO CLIENTE
    Route::get('/cliente_orcamento/{id}/contato', [ContatoClienteController::class, 'index'])
        ->name('contato_cliente.index');
    Route::get('/cliente_orcamento/{id}/contato/create', [ContatoClienteController::class, 'create'])
        ->name('contato_cliente.create');
    Route::resource('contato_cliente', ContatoClienteController::class)
        ->except(['index', 'create']);

    // ORÇAMENTOS
    Route::get('/cliente_orcamento/{id}/orcamento', [OrcamentoController::class, 'index'])->name('orcamento.index');
    Route::get('/cliente_orcamento/{id}/orcamento/create', [OrcamentoController::class, 'create'])->name('orcamento.create');
    Route::resource('orcamento', OrcamentoController::class)->except(['index', 'create']);
    Route::post('/orcamento/{id}/desconto', [OrcamentoController::class, 'aplicarDesconto'])->name('orcamento.desconto');
    Route::get('/orcamento/gerar/{id}', [OrcamentoController::class, 'gerarOrcamento'])->name('gerar_orcamento');
    Route::get('/orcamento/pdf/{id}', [OrcamentoController::class, 'gerarOrcamentoPDF'])->name('gerar_orcamento_pdf');
    Route::get('/orcamento/preview/{id}', [OrcamentoController::class, 'previewOrcamento'])->name('orcamento_preview');

    // DETALHES ORÇAMENTO
    Route::get('/detalhes_orcamento/{id}', [DetalhesOrcamentoController::class, 'index'])->name('detalhes_orcamento.index');
    Route::get('/detalhes_orcamento/create/{id?}', [DetalhesOrcamentoController::class, 'create'])->name('detalhes_orcamento.create');
    Route::get('/detalhes_orcamento/item/{id}', [DetalhesOrcamentoController::class, 'show'])->name('detalhes_orcamento.show');
    Route::resource('detalhes_orcamento', DetalhesOrcamentoController::class)->except(['index', 'create', 'show']);
    Route::get('/detalhes-orcamento/buscar-produtos', [DetalhesOrcamentoController::class, 'buscarProdutos'])->name('detalhes_orcamento.buscar_produtos');

    // CUSTOMIZAÇÕES
    Route::get('/customizacao/detalhe/{id}', [CustomizacaoController::class, 'index'])->name('customizacao.index');
    Route::get('/customizacao/detalhe/{id}/create', [CustomizacaoController::class, 'create'])->name('customizacao.create');
    Route::resource('customizacao', CustomizacaoController::class)->except(['index', 'create']);
    Route::get('/customizacao/{id}/layout', [CustomizacaoController::class, 'camisa'])->name('customizacao.camisa');


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
    Route::get('/fluxo-caixa/pdf', [FluxoCaixaController::class, 'gerarFluxoPdfPorData'])
        ->name('fluxo_caixa.pdf');
    Route::post('/fluxo-caixa/store-fluxo', [FluxoCaixaController::class, 'storeFluxo'])
        ->name('fluxo_caixa.storeFluxo');
    Route::resource('fluxo_caixa', FluxoCaixaController::class);

    // NOTAS FISCAIS
    Route::get('/nota_fiscal/buscar-orcamentos', [NotaFiscalController::class, 'buscarOrcamentos'])->name('nota_fiscal.buscar_orcamentos');
    Route::resource('nota_fiscal', NotaFiscalController::class);

    // CONTA BANCÁRIA
    Route::resource('conta_bancaria', ContaBancariaController::class);
    Route::post('/conta_bancaria/{id}/saldo', [ContaBancariaController::class, 'adicionarSaldo'])
        ->name('conta_bancaria.adicionarSaldo');
    Route::get('/fluxo_nota_conta', function () {
        return view('fluxo_nota_conta');
    })->name('fluxo_nota_conta.index');


    // ADMINISTRACAO
    Route::get('/administracao', function () {
        return view('administracao.index');
    })->name('administracao.index');


    // PRODUTOS
    Route::get('/produtos/{id}/details', [ProdutoController::class, 'getProdutoDetails'])->name('produto.details');
    Route::resource('produto', ProdutoController::class);
    //PREÇO CUSTOMIZAÇÃO
    Route::resource('preco_customizacao', PrecoCustomizacaoController::class);
    //TIPO PAGAMENTO
    Route::resource('tipo_pagamento', TipoPagamentoController::class);



    /* 
        MÓDULO FINANCEIRO
    */

    Route::get('/financeiro', function () {
        return view('financeiro');
    });
    Route::post('/financeiro/{id}/prosseguir', [FinanceiroController::class, 'prosseguir'])->name('financeiro.prosseguir');
    Route::resource('financeiro', FinanceiroController::class);

    // PAGAMENTOS
    Route::resource('forma_pagamento', FormaPagamentoController::class);

    // PARCELAS
    Route::post('/parcelas/{id}/dar-baixa', [FormaPagamentoController::class, 'darBaixa'])->name('parcelas.darBaixa');
    Route::post('/parcelas/{id}/voltar-nao-pago', [FormaPagamentoController::class, 'voltarNaoPago'])->name('parcelas.voltarNaoPago');

    //STATUS E NOTIFICAÇÕES
    Route::resource('log_status', LogStatusController::class);
    Route::resource('status_mercadoria', StatusMercadoriaController::class);
    Route::resource('notificacao', NotificacaoController::class);

    //COBRANCAS
    Route::resource('cobranca', CobrancaController::class);

    // ORÇAMENTO FRACIONADO
    Route::get('/orcamento-fracionado/{id}/create', [OrcamentoFracionadoController::class, 'create'])->name('orcamento.fracionado.create');
    Route::post('/orcamento/{orcamento}/fracionado', [OrcamentoFracionadoController::class, 'store'])->name('orcamento.fracionado.store');
    Route::get('/orcamento-fracionado/visualizar/{id}', [OrcamentoFracionadoController::class, 'visualizar'])->name('orcamento.fracionado.visualizar');
    Route::delete('/orcamento-fracionado/{id}', [OrcamentoFracionadoController::class, 'destroy'])->name('orcamento.fracionado.destroy');
    Route::get('/orcamento-fracionado/{id}', [OrcamentoFracionadoController::class, 'index'])->name('orcamento.fracionado.index');
    Route::get('/orcamento-fracionado/{id}/editar', [OrcamentoFracionadoController::class, 'edit'])->name('orcamento.fracionado.edit');
    Route::put('/orcamento-fracionado/{id}', [OrcamentoFracionadoController::class, 'update'])->name('orcamento.fracionado.update');
    Route::post('/orcamento-fracionado/{id}/desconto', [OrcamentoFracionadoController::class, 'aplicarDesconto'])->name('orcamento.fracionado.desconto');
    Route::post('/orcamento-fracionado/{id}/prosseguir', [OrcamentoFracionadoController::class, 'prosseguir'])->name('orcamento.fracionado.prosseguir');

    // DETALHES DO ORÇAMENTO FRACIONADO
    Route::get('/detalhes-orcamento-fracionado/{id}/create', [DetalhesOrcamentoFracionadoController::class, 'create'])->name('detalhes_orcamento_fracionado.create');
    Route::get('/detalhes-orcamento-fracionado/{id}/show', [DetalhesOrcamentoFracionadoController::class, 'show'])->name('detalhes_orcamento_fracionado.show');
    Route::post('/detalhes-orcamento-fracionado/{id}', [DetalhesOrcamentoFracionadoController::class, 'store'])->name('detalhes_orcamento_fracionado.store');
    Route::delete('/detalhes-orcamento-fracionado/{id}', [DetalhesOrcamentoFracionadoController::class, 'destroy'])->name('detalhes_orcamento_fracionado.destroy');
    Route::get('/detalhes-orcamento-fracionado/{id}', [DetalhesOrcamentoFracionadoController::class, 'index'])->name('detalhes_orcamento_fracionado.index');


    // CUSTOMIZAÇÕES DO ORÇAMENTO FRACIONADO
    Route::get('/customizacao-fracionada', [CustomizacaoFracionadaController::class, 'index'])->name('customizacao_fracionado.index');
    Route::post('/customizacao-fracionada', [CustomizacaoFracionadaController::class, 'store'])->name('customizacao_fracionado.store');
    Route::get('/customizacao-fracionada/{customizacaoFracionada}', [CustomizacaoFracionadaController::class, 'show'])->name('customizacao_fracionado.show');
});


require __DIR__ . '/auth.php';
