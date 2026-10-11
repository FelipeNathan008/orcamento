
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('nota_fiscal', 'nota_id_tipo')) {
            Schema::table('nota_fiscal', function (Blueprint $table) {
                $table->dropForeign(['nota_id_tipo']);
                $table->dropColumn('nota_id_tipo');
            });
        }

        if (Schema::hasColumn('nota_fiscal', 'nota_data')) {
            Schema::table('nota_fiscal', function (Blueprint $table) {
                $table->renameColumn('nota_data', 'nota_data_emissao');
            });
        }

        Schema::table('nota_fiscal', function (Blueprint $table) {
            $table->unsignedInteger('orcamento_id_orcamento')
                ->nullable()
                ->change();
        });

        if (!Schema::hasColumn('nota_fiscal', 'orcamento_id_orcamento_frac')) {
            Schema::table('nota_fiscal', function (Blueprint $table) {
                $table->unsignedInteger('orcamento_id_orcamento_frac')
                    ->nullable();
            });
        }

        Schema::table('nota_fiscal', function (Blueprint $table) {
            $table->foreign('orcamento_id_orcamento_frac')
                ->references('id_orcamento_fracionado')
                ->on('orcamento_fracionado')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('nota_fiscal', function (Blueprint $table) {
            $table->dropForeign(['orcamento_id_orcamento_frac']);
            $table->dropColumn('orcamento_id_orcamento_frac');
        });

        if (Schema::hasColumn('nota_fiscal', 'nota_data_emissao')) {
            Schema::table('nota_fiscal', function (Blueprint $table) {
                $table->renameColumn('nota_data_emissao', 'nota_data');
            });
        }

        Schema::table('nota_fiscal', function (Blueprint $table) {
            $table->unsignedInteger('nota_id_tipo')->nullable();
        });
    }
};