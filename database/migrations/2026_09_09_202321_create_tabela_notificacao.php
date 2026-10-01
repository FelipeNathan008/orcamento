<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificacao', function (Blueprint $table) {
            $table->id('id_notificacao');

            $table->unsignedInteger('id_det_forma');

            $table->integer('not_tipo');

            $table->text('not_descricao')->nullable();

            $table->foreign('id_det_forma')
                ->references('id_det_forma')
                ->on('detalhes_forma_pag')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificacao');
    }
};
