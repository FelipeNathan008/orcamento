<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orcamento', function (Blueprint $table) {
            $table->text('orc_motivo_rejeicao')
                ->nullable()
                ->after('orc_anotacao_geral');
        });
    }

    public function down(): void
    {
        Schema::table('orcamento', function (Blueprint $table) {
            $table->dropColumn('orc_motivo_rejeicao');
        });
    }
};