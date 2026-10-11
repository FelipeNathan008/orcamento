<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_sub_grupo', function (Blueprint $table) {
            $table->id('id_sub_grupo');
            $table->unsignedBigInteger('grupo_principal_id');
            $table->string('sub_grupo_nome', 100);
            $table->timestamps();

            $table->foreign('grupo_principal_id')
                ->references('id_grupo_principal')
                ->on('tipo_grupo_principal')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_sub_grupo');
    }
};