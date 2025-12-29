<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('detalhes_forma_pag', function (Blueprint $table) {
            $table->string('det_situacao', 20)
                  ->default('Não pago')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('detalhes_forma_pag', function (Blueprint $table) {
            $table->string('det_situacao', 10)
                  ->change();
        });
    }
};
