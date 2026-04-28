<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('detalhes_forma_pag', function (Blueprint $table) {
            $table->date('det_forma_data_pagamento')
                  ->nullable()
                  ->after('det_forma_data_venc');
        });
    }

    public function down()
    {
        Schema::table('detalhes_forma_pag', function (Blueprint $table) {
            $table->dropColumn('det_forma_data_pagamento');
        });
    }
};