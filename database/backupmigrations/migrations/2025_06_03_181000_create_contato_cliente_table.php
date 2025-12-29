<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contato_cliente', function (Blueprint $table) {
            $table->increments('id_contato');
            $table->unsignedBigInteger('cliente_orcamento_id_co'); // corrigido
            $table->string('cont_nome', 45);
            $table->string('cont_telefone', 20)->nullable();
            $table->string('cont_celular', 20);
            $table->string('cont_email', 45);
            $table->string('cont_tipo', 25);
            $table->longText('cont_descricao')->nullable();
            $table->timestamps();

            $table->foreign('cliente_orcamento_id_co')
                ->references('id_co')
                ->on('cliente_orcamento')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contato_cliente');
    }
};
