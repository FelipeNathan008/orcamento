<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_sub_categoria', function (Blueprint $table) {
            $table->id('id_sub_categoria');
            $table->unsignedBigInteger('categoria_id');
            $table->string('sub_categoria_nome', 100);
            $table->timestamps();

            $table->foreign('categoria_id')
                ->references('id_categoria')
                ->on('tipo_categoria')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_sub_categoria');
    }
};