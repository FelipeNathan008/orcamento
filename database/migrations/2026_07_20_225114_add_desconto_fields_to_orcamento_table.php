<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orcamento', function (Blueprint $table) {

            $table->enum('orc_desconto_tipo', [
                'valor',
                'percentual'
            ])->nullable()->after('orc_anotacao_geral');

            $table->decimal('orc_desconto_valor',10,2)
                ->default(0)
                ->after('orc_desconto_tipo');

            $table->string('orc_desconto_motivo')
                ->nullable()
                ->after('orc_desconto_valor');
        });
    }

    public function down(): void
    {
        Schema::table('orcamento', function (Blueprint $table) {

            $table->dropColumn([
                'orc_desconto_tipo',
                'orc_desconto_valor',
                'orc_desconto_motivo'
            ]);

        });
    }
};