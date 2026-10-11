
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fluxo_caixa', function (Blueprint $table) {
            $table->dropForeign(['flu_id_tipo']);
            $table->dropColumn('flu_id_tipo');

            $table->foreignId('grupo_principal_id')
                ->nullable()
                ->constrained('tipo_grupo_principal', 'id_grupo_principal')
                ->nullOnDelete();

            $table->foreignId('sub_grupo_id')
                ->nullable()
                ->constrained('tipo_sub_grupo', 'id_sub_grupo')
                ->nullOnDelete();

            $table->foreignId('categoria_id')
                ->nullable()
                ->constrained('tipo_categoria', 'id_categoria')
                ->nullOnDelete();

            $table->foreignId('sub_categoria_id')
                ->nullable()
                ->constrained('tipo_sub_categoria', 'id_sub_categoria')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fluxo_caixa', function (Blueprint $table) {
            $table->dropForeign(['grupo_principal_id']);
            $table->dropForeign(['sub_grupo_id']);
            $table->dropForeign(['categoria_id']);
            $table->dropForeign(['sub_categoria_id']);

            $table->dropColumn([
                'grupo_principal_id',
                'sub_grupo_id',
                'categoria_id',
                'sub_categoria_id',
            ]);

            $table->unsignedBigInteger('flu_id_tipo')->nullable();

            $table->foreign('flu_id_tipo')
                ->references('id_tipo_fluxo')
                ->on('tipo_fluxo_caixa')
                ->nullOnDelete();
        });
    }
};
