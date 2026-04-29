<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conta_bancaria', function (Blueprint $table) {
            $table->string('conta_agencia', 30)->after('conta_cod_banco');
        });
    }

    public function down(): void
    {
        Schema::table('conta_bancaria', function (Blueprint $table) {
            $table->dropColumn('conta_agencia');
        });
    }
};
