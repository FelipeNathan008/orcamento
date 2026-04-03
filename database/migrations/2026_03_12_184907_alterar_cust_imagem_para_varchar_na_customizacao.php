<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customizacao', function (Blueprint $table) {
            $table->string('cust_imagem', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('customizacao', function (Blueprint $table) {
            $table->mediumBlob('cust_imagem')->nullable()->change();
        });
    }
};
