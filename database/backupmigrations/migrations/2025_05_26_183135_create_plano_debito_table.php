    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::create('plano_debito', function (Blueprint $table) {
                $table->increments('id_plano_debito');
                $table->integer('cod_cliente');
                $table->integer('id_orcamento');
                $table->string('nome_cliente', 55);
                $table->string('forma_pagamento', 25);
                $table->date('data_aprovacao');
                $table->date('data_vencimento');
                $table->integer('quantidade_parcelas');
                $table->decimal('valor_parcelas', 10, 2);
                $table->string('status', 25);
                $table->string('descricao', 120)->nullable();
                $table->integer('dias_atraso')->nullable();
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('plano_debito');
        }
    };
