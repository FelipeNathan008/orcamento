<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_categoria', function (Blueprint $table) {
            $table->id('id_categoria');
            $table->unsignedBigInteger('sub_grupo_id');
            $table->string('categoria_nome', 100);
            $table->timestamps();

            $table->foreign('sub_grupo_id')
                ->references('id_sub_grupo')
                ->on('tipo_sub_grupo')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_categoria');
    }
};