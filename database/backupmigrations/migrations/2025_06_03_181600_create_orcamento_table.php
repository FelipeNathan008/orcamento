<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orcamento', function (Blueprint $table) {
            $table->increments('id_orcamento');
            $table->unsignedInteger('cliente_orcamento_id_co');
            $table->date('orc_data_inicio');
            $table->date('orc_data_fim');
            $table->string('orc_status', 20);
            $table->timestamps();

            // FK para cliente_orcamento
            $table->foreign('cliente_orcamento_id_co')
                ->references('id_co')->on('cliente_orcamento')
                ->onDelete('no action')->onUpdate('no action');

            // índice único composto para FK composta nas outras tabelas
            $table->unique(['id_orcamento', 'cliente_orcamento_id_co'], 'unique_orcamento_cliente');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orcamento');
    }
};
