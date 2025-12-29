<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orcamento', function (Blueprint $table) {
            $table->string('orc_anotacao_espec', 1000)->nullable()->after('orc_status');
            $table->string('orc_anotacao_geral', 1000)->nullable()->after('orc_anotacao_espec');
        });
    }

    public function down(): void
    {
        Schema::table('orcamento', function (Blueprint $table) {
            $table->dropColumn(['orc_anotacao_espec', 'orc_anotacao_geral']);
        });
    }
};
