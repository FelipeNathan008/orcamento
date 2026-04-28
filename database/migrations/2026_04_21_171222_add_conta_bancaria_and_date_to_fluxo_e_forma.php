<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // FLUXO CAIXA
        Schema::table('fluxo_caixa', function (Blueprint $table) {

            $table->unsignedBigInteger('conta_bancaria_id')
                ->nullable()
                ->after('flu_id_movimentacao');

            $table->foreign('conta_bancaria_id')
                ->references('id_conta')
                ->on('conta_bancaria')
                ->onDelete('set null');
        });

        // FORMA PAGAMENTO
        Schema::table('forma_pagamento', function (Blueprint $table) {

            $table->unsignedBigInteger('conta_bancaria_id')
                ->nullable()
                ->after('tipo_pagamento_id_tipo');
            $table->foreign('conta_bancaria_id')
                ->references('id_conta')
                ->on('conta_bancaria')
                ->onDelete('set null');
                
            $table->date('forma_data')
                ->nullable()
                ->after('conta_bancaria_id');
        });
    }

    public function down(): void
    {
        // FLUXO CAIXA
        Schema::table('fluxo_caixa', function (Blueprint $table) {
            $table->dropForeign(['conta_bancaria_id']);
            $table->dropColumn('conta_bancaria_id');
        });

        // FORMA PAGAMENTO
        Schema::table('forma_pagamento', function (Blueprint $table) {
            $table->dropForeign(['conta_bancaria_id']);
            $table->dropColumn('conta_bancaria_id');
        });
        Schema::table('forma_pagamento', function (Blueprint $table) {
            $table->dropForeign(['conta_bancaria_id']);
            $table->dropColumn(['conta_bancaria_id', 'forma_data']);
        });
    }
};
