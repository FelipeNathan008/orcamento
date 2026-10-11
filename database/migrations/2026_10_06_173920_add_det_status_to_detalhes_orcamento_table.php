<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalhes_orcamento', function (Blueprint $table) {
            $table->string('det_status', 50)->nullable()->after('det_anotacao');
        });
    }

    public function down(): void
    {
        Schema::table('detalhes_orcamento', function (Blueprint $table) {
            $table->dropColumn('det_status');
        });
    }
};
