<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tabelas = [
            'cliente_orcamento',
            'contato_cliente',
            'orcamento',
            'detalhes_orcamento',
            'log_status_orcamento',
            'customizacao',
            'produto',
            'log_status',
            'financeiro',
            'forma_pagamento',
            'tipo_pagamento',
            'detalhes_forma_pag',
            'cobranca',
            'detalhes_cobranca',
            'notificacao'
        ];

        foreach ($tabelas as $tabela) {
            Schema::table($tabela, function (Blueprint $table) {
                $table->boolean('status_bloqueado')->nullable();
            });
        }
    }

    public function down(): void
    {
        $tabelas = [
            'cliente_orcamento',
            'contato_cliente',
            'orcamento',
            'detalhes_orcamento',
            'log_status_orcamento',
            'customizacao',
            'produto',
            'log_status',
            'financeiro',
            'forma_pagamento',
            'tipo_pagamento',
            'detalhes_forma_pag',
            'cobranca',
            'detalhes_cobranca',
            'notificacao'
        ];

        foreach ($tabelas as $tabela) {
            Schema::table($tabela, function (Blueprint $table) {
                $table->dropColumn('status_bloqueado');
            });
        }
    }
};
