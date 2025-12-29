<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_status_orcamento', function (Blueprint $table) {
            $table->id('id_log');
            $table->unsignedInteger('orcamento_id_orcamento');
            $table->string('log_status', 20);
            $table->string('log_motivo', 200);
            $table->timestamps();

            $table->foreign('orcamento_id_orcamento')
                ->references('id_orcamento')
                ->on('orcamento')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_status_orcamento');
    }
};
