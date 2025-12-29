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
        Schema::create('detalhes_forma_pag', function (Blueprint $table) {
            $table->increments('id_det_forma');

            $table->unsignedInteger('id_forma_pag');

            $table->decimal('det_forma_valor_parcela', 10, 2);
            $table->date('det_forma_data_venc');

            $table->foreign('id_forma_pag')
                ->references('id_forma_pag')->on('forma_pagamento');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalhes_forma_pag');
    }
};
