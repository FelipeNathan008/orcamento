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
        Schema::table('orcamento', function (Blueprint $table) {
            $table->string('orc_cod_interno', 60)
                ->nullable()
                ->unique()
                ->after('orc_cod_fabrica');
        });
    }

    public function down(): void
    {
        Schema::table('orcamento', function (Blueprint $table) {
            $table->dropColumn('orc_cod_interno');
        });
    }
};
