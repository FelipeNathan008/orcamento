<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('saldo_conta', function (Blueprint $table) {

            $table->id('id_saldo_conta');

            $table->unsignedBigInteger('id_conta_bancaria_id');

            $table->decimal('saldo_conta_valor', 10, 2)->default(0);

            $table->timestamps();

            // FK
            $table->foreign('id_conta_bancaria_id')
                ->references('id_conta')
                ->on('conta_bancaria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldo_conta');
    }
};