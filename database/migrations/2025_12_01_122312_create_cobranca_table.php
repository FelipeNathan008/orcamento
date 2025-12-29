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
        Schema::create('cobranca', function (Blueprint $table) {
            $table->increments('id_cobranca');
            $table->string('cobr_cliente');
            $table->integer('cobr_id_fin');
            $table->integer('cobr_id_orc');
            $table->integer('cobr_id_tipo');
            $table->string('cobr_status', 45);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cobranca');
    }
};
