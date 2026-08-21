<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ORÇAMENTO FRACIONADO
        |--------------------------------------------------------------------------
        */
        Schema::create('orcamento_fracionado', function (Blueprint $table) {

            $table->increments('id_orcamento_fracionado');

            $table->unsignedInteger('orcamento_id_orcamento');
            $table->integer('orc_fracao');
            $table->unsignedInteger('cliente_orcamento_id_co');

            $table->date('orc_data_inicio')->nullable();
            $table->date('orc_data_fim')->nullable();

            $table->string('orc_status')->nullable();

            $table->text('orc_anotacao_espec')->nullable();
            $table->text('orc_anotacao_geral')->nullable();
            $table->text('orc_motivo_rejeicao')->nullable();

            $table->string('orc_cod_fabrica')->nullable();
            $table->string('orc_cod_interno')->nullable();

            $table->string('orc_desconto_tipo')->nullable();
            $table->decimal('orc_desconto_valor', 10, 2)->default(0);
            $table->text('orc_desconto_motivo')->nullable();

            $table->timestamps();

            $table->foreign('orcamento_id_orcamento', 'orcamento_fracionado_orcamento_fk')
                ->references('id_orcamento')
                ->on('orcamento')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('cliente_orcamento_id_co', 'orcamento_fracionado_cliente_fk')
                ->references('id_co')
                ->on('cliente_orcamento')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        /*
        |--------------------------------------------------------------------------
        | DETALHES ORÇAMENTO FRACIONADO
        |--------------------------------------------------------------------------
        */
        Schema::create('detalhes_orcamento_fracionado', function (Blueprint $table) {

            $table->increments('id_det_fracionado');

            $table->unsignedInteger('orcamento_fracionado_id');
            $table->unsignedInteger('orcamento_cliente_orcamento_id_co');
            $table->unsignedInteger('produto_id_produto');

            $table->string('det_cod', 45)->nullable();
            $table->string('det_categoria', 50)->nullable();
            $table->string('det_modelo', 70)->nullable();
            $table->string('det_cor', 20)->nullable();
            $table->string('det_tamanho', 45)->nullable();

            $table->integer('det_quantidade');

            $table->decimal('det_valor_unit', 10, 2);

            $table->string('det_genero', 20)->nullable();
            $table->string('det_caract', 65)->nullable();

            $table->string('det_observacao', 200)->nullable();
            $table->string('det_anotacao', 200)->nullable();

            $table->unsignedInteger('orcamento_cliente_id_cliente')->nullable();

            $table->timestamps();

            $table->foreign('orcamento_fracionado_id', 'det_orc_fracionado_orcamento_fk')
                ->references('id_orcamento_fracionado')
                ->on('orcamento_fracionado')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('produto_id_produto', 'det_orc_fracionado_produto_fk')
                ->references('id_produto')
                ->on('produto')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('orcamento_cliente_orcamento_id_co', 'det_orc_fracionado_cliente_fk')
                ->references('id_co')
                ->on('cliente_orcamento')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->unsignedInteger('detalhes_orcamento_id_det');

            $table->foreign('detalhes_orcamento_id_det', 'det_orc_fracionado_original_fk')
                ->references('id_det')
                ->on('detalhes_orcamento')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        /*
        |--------------------------------------------------------------------------
        | CUSTOMIZAÇÃO FRACIONADA
        |--------------------------------------------------------------------------
        */
        Schema::create('customizacao_fracionada', function (Blueprint $table) {

            $table->increments('id_customizacao_fracionada');

            $table->unsignedInteger('detalhes_orcamento_fracionado_id');

            $table->string('cust_tipo', 45)->nullable();
            $table->string('cust_local', 45)->nullable();
            $table->string('cust_posicao', 45)->nullable();
            $table->string('cust_tamanho', 45)->nullable();
            $table->string('cust_formatacao', 45)->nullable();

            $table->text('cust_descricao')->nullable();
            $table->string('cust_imagem')->nullable();

            $table->decimal('cust_valor', 10, 2)->default(0);

            $table->timestamps();

            $table->foreign('detalhes_orcamento_fracionado_id', 'customizacao_fracionada_det_fk')
                ->references('id_det_fracionado')
                ->on('detalhes_orcamento_fracionado')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customizacao_fracionada');
        Schema::dropIfExists('detalhes_orcamento_fracionado');
        Schema::dropIfExists('orcamento_fracionado');
    }
};
