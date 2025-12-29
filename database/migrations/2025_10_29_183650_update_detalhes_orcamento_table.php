<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('detalhes_orcamento', function (Blueprint $table) {
            $table->string('det_tamanho', 10)->change();
            $table->string('det_caract', 255)->change();
            $table->unsignedInteger('orcamento_cliente_orcamento_id_co')->nullable();
            $table->unsignedInteger('orcamento_cliente_id_cliente')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('detalhes_orcamento', function (Blueprint $table) {
            $table->string('det_tamanho', 45)->change();
            $table->string('det_caract', 65)->change();
            $table->dropColumn(['orcamento_cliente_orcamento_id_co', 'orcamento_cliente_id_cliente']);
        });
    }
};
