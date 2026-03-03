<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela cliente_orcamento
        Schema::table('cliente_orcamento', function (Blueprint $table) {
            $table->string('clie_orc_cod_interno', 60)
                  ->after('clie_orc_uf');
        });

        // Tabela orcamento
        Schema::table('orcamento', function (Blueprint $table) {
            $table->string('orc_cod_fabrica', 60)
                  ->nullable()
                  ->after('orc_anotacao_geral');
        });
    }

    public function down(): void
    {
        Schema::table('cliente_orcamento', function (Blueprint $table) {
            $table->dropColumn('clie_orc_cod_interno');
        });

        Schema::table('orcamento', function (Blueprint $table) {
            $table->dropColumn('orc_cod_fabrica');
        });
    }
};
