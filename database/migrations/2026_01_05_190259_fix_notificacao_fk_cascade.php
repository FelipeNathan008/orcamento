<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notificacao', function (Blueprint $table) {

            // Remove a FK antiga
            $table->dropForeign(['cobranca_id_cobranca']);

            // Cria a FK correta com CASCADE
            $table->foreign('cobranca_id_cobranca')
                  ->references('id_cobranca')
                  ->on('cobranca')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('notificacao', function (Blueprint $table) {

            // Remove a FK cascade
            $table->dropForeign(['cobranca_id_cobranca']);

            // Volta para o comportamento antigo
            $table->foreign('cobranca_id_cobranca')
                  ->references('id_cobranca')
                  ->on('cobranca')
                  ->onDelete('no action')
                  ->onUpdate('no action');
        });
    }
};
