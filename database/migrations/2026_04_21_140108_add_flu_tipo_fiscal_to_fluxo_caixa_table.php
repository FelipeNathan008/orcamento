<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fluxo_caixa', function (Blueprint $table) {
            $table->string('flu_tipo_fiscal', 90)
                  ->before('flu_num_doc')
                  ->nullable(false);
        });
    }

    public function down(): void
    {
        Schema::table('fluxo_caixa', function (Blueprint $table) {
            $table->dropColumn('flu_tipo_fiscal');
        });
    }
};