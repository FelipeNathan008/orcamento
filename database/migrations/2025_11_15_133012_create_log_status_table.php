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
        Schema::create('log_status', function (Blueprint $table) {
            $table->increments('id_log_status');

            $table->unsignedInteger('status_mercadoria_id_status');
            $table->integer('log_id_orcamento');
            $table->integer('log_id_cliente');

            $table->string('log_nome_status', 90);
            $table->tinyInteger('log_situacao');

            $table->foreign('status_mercadoria_id_status')
                ->references('id_status_merc')->on('status_mercadoria');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_status');
    }
};
