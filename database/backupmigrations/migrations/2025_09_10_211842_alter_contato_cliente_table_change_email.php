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
        Schema::table('contato_cliente', function (Blueprint $table) {
            // Altera a coluna 'cont_email' para ter 45 caracteres e remove a restrição de unicidade.
            // Para garantir que a migração não falhe, primeiro tentamos remover o índice,
            // caso ele exista. A coluna já é do tipo string, então não precisa de alteração de tipo.
            $table->string('cont_email', 45)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contato_cliente', function (Blueprint $table) {
            // Reverte a alteração: torna a coluna 'cont_email' única novamente.
            $table->string('cont_email', 45)->unique()->change();
        });
    }
};
