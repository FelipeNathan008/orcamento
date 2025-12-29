<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cliente_orcamento', function (Blueprint $table) {
            $table->id('id_co');
            $table->string('clie_orc_nome', 45);
            $table->string('clie_orc_email', 45);
            $table->string('clie_orc_bairro', 45);
            $table->string('clie_orc_logradouro', 45);
            $table->string('clie_orc_cidade', 45);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_orcamento');
    }
};

