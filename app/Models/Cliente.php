<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente'; // Confirme que o nome da tabela está correto
    protected $primaryKey = 'id_cliente'; // Confirme que a chave primária está correta

    // Adicione 'clie_cpf', 'clie_cnpj' e 'clie_rg' (se estiver usando) ao array $fillable
    protected $fillable = [
        'clie_nome',
        'clie_email',
        'clie_telefone', // Confirme que este nome está correto e na tabela
        'clie_celular', 
        'clie_logradouro',
        'clie_bairro',
        'clie_tipo_doc', // Este campo também precisa estar no fillable
        'clie_cpf',
        'clie_cnpj',
        'clie_cep',
        'clie_cidade',
        'clie_uf',
        // Adicione quaisquer outras colunas que você esteja tentando salvar via mass assignment
    ];

    // Se você estiver usando $guarded em vez de $fillable, certifique-se de que esses campos NÃO estão no $guarded
    // protected $guarded = ['id_cliente']; // Exemplo: todos os campos são fillable exceto a PK
}
