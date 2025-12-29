<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customizacao', function (Blueprint $table) {
            $table->increments('id_customizacao');
            $table->unsignedInteger('detalhes_orcamento_id_det');
            $table->string('cust_tipo', 45);
            $table->string('cust_local', 25);
            $table->string('cust_posicao', 25);
            $table->string('cust_tamanho', 30);
            $table->string('cust_formatacao', 45);
            $table->string('cust_descricao', 90);
            $table->binary('cust_imagem');
            $table->timestamps();

            $table->foreign('detalhes_orcamento_id_det')
                ->references('id_det')->on('detalhes_orcamento')
                ->onDelete('no action')->onUpdate('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customizacao');
    }
};
