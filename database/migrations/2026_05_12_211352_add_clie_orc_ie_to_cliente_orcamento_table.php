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
        Schema::table('cliente_orcamento', function (Blueprint $table) {
            $table->string('clie_orc_ie', 90)
                  ->nullable(false)
                  ->after('clie_orc_cnpj');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cliente_orcamento', function (Blueprint $table) {
            $table->dropColumn('clie_orc_ie');
        });
    }
};
