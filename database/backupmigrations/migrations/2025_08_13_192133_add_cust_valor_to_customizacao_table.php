<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customizacao', function (Blueprint $table) {
            // Adiciona a nova coluna `cust_valor` com o tipo decimal.
            $table->decimal('cust_valor', 8, 2)->after('cust_descricao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customizacao', function (Blueprint $table) {
            // Remove a coluna `cust_valor` para reverter a migração.
            $table->dropColumn('cust_valor');
        });
    }
};
