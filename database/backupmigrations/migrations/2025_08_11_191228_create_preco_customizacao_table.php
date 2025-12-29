<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('preco_customizacao', function (Blueprint $table) {
            $table->increments('id_preco');
            $table->string('preco_tipo', 45);
            $table->string('preco_tamanho', 30);
            $table->decimal('preco_valor', 8, 2); // Exemplo de decimal com 8 dígitos e 2 casas
            $table->timestamps(); // Opcional, para created_at e updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('preco_customizacao');
    }
};
