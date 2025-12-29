<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('preco_customizacao', function (Blueprint $table) {
            $table->increments('id_preco');
            $table->string('preco_tipo', 45);
            $table->string('preco_tamanho', 30);
            $table->decimal('preco_valor', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preco_customizacao');
    }
};
