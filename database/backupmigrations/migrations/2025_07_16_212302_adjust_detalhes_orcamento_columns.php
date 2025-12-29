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
            // Altera 'det_modelo' para VARCHAR(70)
            $table->string('det_modelo', 70)->change();

            // Altera 'det_cor' para VARCHAR(20)
            $table->string('det_cor', 20)->change();

            // Altera 'det_tamanho' para VARCHAR(10)
            $table->string('det_tamanho', 10)->change();

            // Altera 'det_observacao' para VARCHAR(255)
            $table->string('det_observacao', 255)->nullable()->change();

            // Altera 'det_anotacao' para VARCHAR(255)
            $table->string('det_anotacao', 255)->nullable()->change();

            // Altera 'det_caract' para VARCHAR(255) (para garantir a consistência)
            $table->string('det_caract', 255)->change();

            // Altera 'orcamento_cliente_id_cliente' para INT UNSIGNED
            // IMPORTANTE: Se esta coluna já tiver dados negativos, esta alteração pode falhar.
            // Certifique-se de que os dados existentes são compatíveis.
            $table->unsignedInteger('orcamento_cliente_id_cliente')->change();
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
            // Reverte 'det_modelo' para o tamanho original (ex: 45)
            $table->string('det_modelo', 45)->change();

            // Reverte 'det_cor' para o tamanho original (ex: 15)
            $table->string('det_cor', 15)->change();

            // Reverte 'det_tamanho' para o tamanho original (ex: 5)
            $table->string('det_tamanho', 5)->change();

            // Reverte 'det_observacao' para o tamanho original (ex: 200)
            $table->string('det_observacao', 200)->nullable()->change();

            // Reverte 'det_anotacao' para o tamanho original (ex: 200)
            $table->string('det_anotacao', 200)->nullable()->change();

            // Reverte 'det_caract' para o tamanho original (ex: 55 ou o que era antes)
            $table->string('det_caract', 55)->change(); // Ajuste para o tamanho original, se diferente

            // Reverte 'orcamento_cliente_id_cliente' para INT
            $table->integer('orcamento_cliente_id_cliente')->change();
        });
    }
};
