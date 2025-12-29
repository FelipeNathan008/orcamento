<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notificacao', function (Blueprint $table) {
            $table->increments('id_notificacao');
            $table->unsignedInteger('cobranca_id_cobranca'); // FK
            $table->string('not_tipo', 45);
            $table->text('not_descricao');

            $table->foreign('cobranca_id_cobranca')
                  ->references('id_cobranca')
                  ->on('cobranca')
                  ->onDelete('no action')
                  ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('notificacao');
    }
};
