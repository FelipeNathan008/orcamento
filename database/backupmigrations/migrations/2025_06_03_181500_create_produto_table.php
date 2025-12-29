<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('produto', function (Blueprint $table) {
            $table->increments('id_produto');
            $table->string('prod_cod', 45);
            $table->string('prod_nome', 45);
            $table->string('prod_familia', 25);
            $table->string('prod_categoria', 45);
            $table->string('prod_material', 45);
            $table->string('prod_genero', 20);
            $table->string('prod_modelo', 20);
            $table->string('prod_caract', 45);
            $table->string('prod_cor', 20);
            $table->decimal('prod_preco', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produto');
    }
};
