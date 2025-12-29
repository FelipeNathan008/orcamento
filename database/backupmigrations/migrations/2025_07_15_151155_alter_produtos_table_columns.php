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
        Schema::table('produto', function (Blueprint $table) {
            // Altera o campo prod_modelo para aceitar até 70 caracteres
            $table->string('prod_modelo', 70)->change();

            // Altera o campo prod_caract para aceitar até 55 caracteres
            // Considere usar 'text' se a lista de características puder ser muito longa.
            // Por enquanto, string(55) deve ser suficiente para as opções atuais.
            $table->string('prod_caract', 55)->change();

            // Para prod_tamanho, se ele armazena múltiplos valores separados por vírgula,
            // é melhor usar um campo 'text' para maior flexibilidade, caso a lista cresça.
            // Se você tem certeza que 255 caracteres são suficientes, pode usar string(255).
            // Vou sugerir 'text' para ser mais robusto.
            $table->text('prod_tamanho')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produto', function (Blueprint $table) {
            // Reverte as alterações (opcional, mas boa prática)
            $table->string('prod_modelo', 20)->change(); // Reverter para o tamanho original
            $table->string('prod_caract', 45)->change(); // Reverter para o tamanho original
            $table->string('prod_tamanho', 45)->change(); // Reverter para o tamanho original (ou o que era antes)
        });
    }
};