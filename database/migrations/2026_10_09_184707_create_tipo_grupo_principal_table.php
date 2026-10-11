<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_grupo_principal', function (Blueprint $table) {
            $table->id('id_grupo_principal');
            $table->string('grupo_principal_nome', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_grupo_principal');
    }
};
