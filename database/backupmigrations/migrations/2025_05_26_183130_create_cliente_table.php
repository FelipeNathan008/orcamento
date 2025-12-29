<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cliente', function (Blueprint $table) {
            $table->increments('id_cliente');
            $table->string('clie_nome', 45);
            $table->string('clie_email', 45)->unique();
            $table->string('clie_telefone', 20)->nullable();
            $table->string('clie_celular', 45)->nullable();
            $table->string('clie_tipo_doc', 10);
            $table->string('clie_cnpj', 18)->nullable();
            $table->string('clie_cpf', 14)->nullable();
            $table->string('clie_logradouro', 45);
            $table->string('clie_bairro', 45);
            $table->string('clie_cidade', 45);
            $table->string('clie_uf', 2);
            $table->string('clie_cep', 45);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente');
    }
};
