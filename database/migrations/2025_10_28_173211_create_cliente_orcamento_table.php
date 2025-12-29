<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cliente_orcamento', function (Blueprint $table) {
            $table->increments('id_co');
            $table->string('clie_orc_nome', 90);
            $table->string('clie_orc_email', 90);
            $table->string('clie_orc_telefone', 30)->nullable();
            $table->string('clie_orc_celular', 30);
            $table->string('clie_orc_tipo_doc', 5);
            $table->string('clie_orc_cnpj', 20)->nullable();
            $table->string('clie_orc_cpf', 20)->nullable();
            $table->string('clie_orc_logradouro', 90);
            $table->string('clie_orc_bairro', 90);
            $table->string('clie_orc_cep', 15);
            $table->string('clie_orc_cidade', 90);
            $table->string('clie_orc_uf', 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_orcamento');
    }
};
