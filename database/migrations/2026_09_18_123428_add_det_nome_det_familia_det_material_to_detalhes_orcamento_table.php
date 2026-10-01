<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalhes_orcamento', function (Blueprint $table) {
            $table->string('det_nome', 255)->after('det_cod');
            $table->string('det_familia', 45)->after('det_nome');
            $table->string('det_material', 255)->nullable()->after('det_familia');
        });
    }

    public function down(): void
    {
        Schema::table('detalhes_orcamento', function (Blueprint $table) {
            $table->dropColumn([
                'det_nome',
                'det_familia',
                'det_material',
            ]);
        });
    }
};