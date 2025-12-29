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
        Schema::create('financeiro', function (Blueprint $table) {
            $table->increments('id_fin');

            $table->unsignedInteger('orcamento_id_orcamento');
            $table->integer('id_orcamento');
            $table->integer('id_cliente');

            $table->string('fin_nome_cliente', 90);
            $table->decimal('fin_valor_total', 10, 2);
            $table->string('fin_status', 45);

            $table->foreign('orcamento_id_orcamento')
                ->references('id_orcamento')->on('orcamento');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financeiro');
    }
};
