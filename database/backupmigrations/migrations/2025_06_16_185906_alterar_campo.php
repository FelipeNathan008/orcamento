<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Altera a coluna cust_imagem para MEDIUMBLOB usando SQL bruto
        DB::statement('ALTER TABLE customizacao MODIFY cust_imagem MEDIUMBLOB');
    }

    public function down(): void
    {
        // Reverte para BLOB padrão (usado por $table->binary())
        DB::statement('ALTER TABLE customizacao MODIFY cust_imagem BLOB');
    }
};
