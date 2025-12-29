<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detalhes_orcamento', function (Blueprint $table) {
            // Altera a coluna 'det_caract' para VARCHAR(255)
            // Certifique-se de usar 'string' e o mesmo 'nullable' (se aplicável)
            // que a coluna original, ou defina como necessário.
            // Se a coluna já tiver dados, certifique-se de que o novo tamanho
            // é grande o suficiente para os dados existentes.
            $table->string('det_caract', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detalhes_orcamento', function (Blueprint $table) {
            // Reverte a coluna 'det_caract' para o tamanho original (ex: 45)
            // Se você não souber o tamanho original, pode precisar verificar
            // a migração que criou a tabela 'detalhes_orcamento'.
            $table->string('det_caract', 45)->change(); // Altere 45 para o tamanho original se for diferente
        });
    }
};
