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
        Schema::table('cliente', function (Blueprint $table) {
            $table->string('clie_nome', 80)->change();
            $table->string('clie_email', 85)->change();
            $table->string('clie_bairro', 80)->change();
            $table->string('clie_logradouro', 100)->change();
            $table->string('clie_cidade', 60)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cliente', function (Blueprint $table) {
            $table->string('clie_nome', 45)->change();
            $table->string('clie_email', 45)->change();
            $table->string('clie_bairro', 45)->change();
            $table->string('clie_logradouro', 45)->change();
            $table->string('clie_cidade', 45)->change();
        });
    }
};
