<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalhes_forma_pag', function (Blueprint $table) {
            $table->decimal('det_forma_valor_original', 15, 2)
                ->after('id_forma_pag');
        });
    }

    public function down(): void
    {
        Schema::table('detalhes_forma_pag', function (Blueprint $table) {
            $table->dropColumn('det_forma_valor_original');
        });
    }
};