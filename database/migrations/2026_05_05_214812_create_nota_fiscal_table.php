<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nota_fiscal', function (Blueprint $table) {

            $table->id('id_nota_fiscal');

            $table->unsignedInteger('orcamento_id_orcamento');
            $table->string('nota_numero', 100);
            $table->unsignedBigInteger('nota_id_tipo');
            $table->date('nota_data');
            $table->unsignedBigInteger('nota_id_movimentacao');
            $table->decimal('nota_valor', 10, 2);
            $table->string('nota_desc', 255);

            // FKs
            $table->foreign('orcamento_id_orcamento')
                ->references('id_orcamento')
                ->on('orcamento');

            $table->foreign('nota_id_tipo')
                ->references('id_tipo_fluxo')
                ->on('tipo_fluxo_caixa');

            $table->foreign('nota_id_movimentacao')
                ->references('id_movimentacao')
                ->on('movimentacao');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nota_fiscal');
    }
};
