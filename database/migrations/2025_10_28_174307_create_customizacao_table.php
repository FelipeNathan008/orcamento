<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
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
            $table->decimal('cust_valor', 8, 2); // coluna já incluída
            $table->binary('cust_imagem'); // será alterada para MEDIUMBLOB depois
            $table->timestamps();

            $table->foreign('detalhes_orcamento_id_det')
                ->references('id_det')->on('detalhes_orcamento')
                ->onDelete('no action')->onUpdate('no action');
        });

        // Altera a coluna cust_imagem para MEDIUMBLOB usando SQL bruto
        DB::statement('ALTER TABLE customizacao MODIFY cust_imagem MEDIUMBLOB');
    }

    public function down(): void
    {
        Schema::dropIfExists('customizacao');
    }
};
