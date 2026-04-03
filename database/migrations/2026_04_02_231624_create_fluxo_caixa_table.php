<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fluxo_caixa', function (Blueprint $table) {
            $table->id('id_fluxo');

            $table->date('flu_data_despesa');

            $table->unsignedBigInteger('flu_id_tipo');
            $table->unsignedBigInteger('flu_id_movimentacao');

            $table->decimal('flu_valor', 10, 2);

            $table->string('flu_num_doc', 255)->nullable();
            $table->string('flu_desc', 180);

            $table->timestamps();

            $table->foreign('flu_id_tipo')
                  ->references('id_tipo_fluxo')
                  ->on('tipo_fluxo_caixa')
                  ->onDelete('cascade');

            $table->foreign('flu_id_movimentacao')
                  ->references('id_movimentacao')
                  ->on('movimentacao')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fluxo_caixa');
    }
};