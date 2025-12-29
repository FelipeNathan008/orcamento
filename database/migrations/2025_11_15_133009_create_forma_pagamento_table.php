<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('forma_pagamento', function (Blueprint $table) {
            $table->increments('id_forma_pag');

            $table->unsignedInteger('financeiro_id_fin');
            $table->unsignedInteger('tipo_pagamento_id_tipo');

            $table->decimal('forma_valor', 10, 2);
            $table->integer('forma_mes');
            $table->string('forma_descricao', 120);
            $table->string('forma_prazo', 45);
            $table->integer('forma_qtd_parcela');

            $table->foreign('financeiro_id_fin')
                ->references('id_fin')->on('financeiro');

            $table->foreign('tipo_pagamento_id_tipo')
                ->references('id_tipo_pagamento')->on('tipo_pagamento');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forma_pagamento');
    }
};
