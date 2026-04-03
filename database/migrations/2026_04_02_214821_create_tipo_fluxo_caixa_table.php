<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tipo_fluxo_caixa', function (Blueprint $table) {
            $table->id('id_tipo_fluxo');
            $table->string('tipo_flu_nome', 120);
            $table->string('tipo_despesa', 90);
            $table->string('tipo_desc', 180);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_fluxo_caixa');
    }
};