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
        Schema::create('detalhes_cobranca', function (Blueprint $table) {
            $table->increments('id_det_cobranca');
            $table->unsignedInteger('cobranca_id'); // FK
            $table->string('det_cobr_valor_parcela', 45);
            $table->date('det_cobr_data_venc');

            $table->foreign('cobranca_id')
                  ->references('id_cobranca')
                  ->on('cobranca')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detales_cobranca');
    }
};