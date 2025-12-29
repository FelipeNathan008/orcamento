<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->increments('id_emp');
            $table->string('emp_nome', 90);
            $table->string('emp_cnpj', 45);
            $table->string('emp_logradouro', 90);
            $table->string('emp_bairro', 90);
            $table->string('emp_cep', 12);
            $table->string('emp_cidade', 90);
            $table->string('emp_uf', 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
