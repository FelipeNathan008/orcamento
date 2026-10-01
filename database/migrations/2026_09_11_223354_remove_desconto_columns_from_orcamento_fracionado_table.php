<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orcamento_fracionado', function (Blueprint $table) {
            $table->dropColumn([
                'orc_desconto_tipo',
                'orc_desconto_valor',
                'orc_desconto_motivo',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('orcamento_fracionado', function (Blueprint $table) {
            $table->string('orc_desconto_tipo')->nullable();
            $table->decimal('orc_desconto_valor', 10, 2)->nullable();
            $table->text('orc_desconto_motivo')->nullable();
        });
    }
};
