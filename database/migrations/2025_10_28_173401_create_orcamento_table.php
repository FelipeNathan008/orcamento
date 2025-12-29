<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orcamento', function (Blueprint $table) {
            $table->increments('id_orcamento');
            $table->unsignedInteger('cliente_orcamento_id_co');
            $table->date('orc_data_inicio');
            $table->date('orc_data_fim');
            $table->string('orc_status', 20);
            $table->timestamps();

            $table->foreign('cliente_orcamento_id_co')
                ->references('id_co')
                ->on('cliente_orcamento')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_status_orcamento'); // dropar tabela dependente primeiro

        Schema::dropIfExists('orcamento');
    }
};
