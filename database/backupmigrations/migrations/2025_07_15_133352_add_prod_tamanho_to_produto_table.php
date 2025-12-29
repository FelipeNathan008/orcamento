<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Altera a tabela 'produto' para adicionar a nova coluna
        Schema::table('produto', function (Blueprint $table) {
            // Adiciona a coluna 'prod_tamanho' como VARCHAR(45) e NOT NULL
            // O after('prod_cor') é opcional, mas ajuda a manter a ordem das colunas se desejar
            $table->string('prod_tamanho', 45)->after('prod_cor')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverte a alteração removendo a coluna 'prod_tamanho'
        Schema::table('produto', function (Blueprint $table) {
            $table->dropColumn('prod_tamanho');
        });
    }
};
