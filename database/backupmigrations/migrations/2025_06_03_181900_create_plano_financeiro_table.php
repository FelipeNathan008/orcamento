<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plano_financeiro', function (Blueprint $table) {
            $table->increments('id_plano_fin');
            $table->unsignedInteger('orcamento_id_orcamento');
            $table->unsignedInteger('orcamento_cliente_orcamento_id_co');
            $table->string('plano_fin_forma_pag', 25);
            $table->string('plano_fin_status', 25);
            $table->date('plano_fin_data_aprovacao');
            $table->decimal('plano_fin_valor_total', 10, 2);
            $table->integer('plano_fin_quant_parc');
            $table->decimal('plano_fin_valor_parcela', 10, 2);
            $table->decimal('plano_fin_valor_entrada', 10, 2)->nullable();
            $table->timestamps();

            // Foreign key com nome curto para evitar erro
            $table->foreign(['orcamento_id_orcamento', 'orcamento_cliente_orcamento_id_co'], 'fk_pf_orcamento')
                ->references(['id_orcamento', 'cliente_orcamento_id_co'])
                ->on('orcamento')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plano_financeiro');
    }
};
