<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detalhes_orcamento', function (Blueprint $table) {
            $table->increments('id_det');
            $table->unsignedInteger('orcamento_id_orcamento');
            $table->unsignedInteger('produto_id_produto');
            $table->string('det_cod', 45);
            $table->string('det_categoria', 50);
            $table->string('det_modelo', 70);
            $table->string('det_cor', 20);
            $table->string('det_tamanho', 45);
            $table->integer('det_quantidade');
            $table->decimal('det_valor_unit', 10, 2);
            $table->string('det_genero', 20);
            $table->string('det_caract', 65);
            $table->string('det_observacao', 200)->nullable();
            $table->string('det_anotacao', 200)->nullable();
            $table->timestamps();

            $table->foreign('orcamento_id_orcamento')
                ->references('id_orcamento')->on('orcamento')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('produto_id_produto')
                ->references('id_produto')->on('produto')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalhes_orcamento');
    }
};
