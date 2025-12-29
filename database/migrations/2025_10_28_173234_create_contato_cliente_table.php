<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contato_cliente', function (Blueprint $table) {
            $table->increments('id_contato');
            $table->unsignedInteger('cliente_orcamento_id_co');
            $table->string('cont_nome', 90);
            $table->string('cont_telefone', 30)->nullable();
            $table->string('cont_celular', 30);
            $table->string('cont_email', 90);
            $table->string('cont_tipo', 25);
            $table->text('cont_descricao')->nullable();
            $table->timestamps();

            $table->foreign('cliente_orcamento_id_co')
                  ->references('id_co')
                  ->on('cliente_orcamento')
                  ->onDelete('no action')
                  ->onUpdate('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contato_cliente');
    }
};
