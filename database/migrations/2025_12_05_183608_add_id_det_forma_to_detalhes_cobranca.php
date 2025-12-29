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
        Schema::table('detalhes_cobranca', function (Blueprint $table) {

            // Campo novo
            $table->unsignedInteger('id_det_forma')
                  ->nullable()                      // pode ser null até atualizar os dados
                  ->after('det_cobr_data_venc');    // posição do campo

            // Foreign key para detalhes_forma_pag
            $table->foreign('id_det_forma')
                  ->references('id_det_forma')
                  ->on('detalhes_forma_pag')
                  ->onDelete('set null');          // se apagar detalhes_forma_pag, não quebra
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalhes_cobranca', function (Blueprint $table) {

            // Primeiro remove FK
            $table->dropForeign(['id_det_forma']);

            // Depois remove o campo
            $table->dropColumn('id_det_forma');
        });
    }
};
