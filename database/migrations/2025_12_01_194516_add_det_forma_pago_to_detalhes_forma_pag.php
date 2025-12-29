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
            $table->string('det_cobr_status', 20)
                  ->default('Débito')
                  ->after('det_cobr_data_venc'); // Coloca depois da data de vencimento
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalhes_cobranca', function (Blueprint $table) {
            $table->dropColumn('det_cobr_status');
        });
    }
};
