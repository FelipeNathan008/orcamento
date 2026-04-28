<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conta_bancaria', function (Blueprint $table) {
            $table->id('id_conta');

            $table->string('conta_nome_banco', 200);
            $table->string('conta_cod_banco', 10);
            $table->string('numero_conta_corrente', 100);
            $table->string('numero_digito_corrente', 90);
            $table->string('conta_desc', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conta_bancaria');
    }
};
