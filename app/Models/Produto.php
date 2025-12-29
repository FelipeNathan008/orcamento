<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    // Adicione esta linha para especificar o nome da tabela no singular
    protected $table = 'produto'; // <-- ESTA LINHA É CRÍTICA E DEVE ESTAR AQUI

    protected $primaryKey = 'id_produto'; // Certifique-se de que a chave primária está correta

    protected $fillable = [
        'prod_nome',
        'prod_familia',
        'prod_categoria',
        'prod_cod',
        'prod_material',
        'prod_preco',
        'prod_genero',
        'prod_modelo',
        'prod_caract',
        'prod_cor',
        'prod_tamanho',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'prod_tamanho' => 'array', // Converte a string de tamanhos para array
        'prod_caract' => 'string',  // Mantido como string, pois é salvo como string separada por vírgulas
        'prod_preco' => 'float',   // Garante que o preço seja tratado como float
    ];

    // Se você estiver salvando 'prod_caract' como uma string separada por vírgulas,
    // o cast 'string' é o correto aqui. Se você mudar para JSON no futuro, altere para 'array' ou 'json'.
}
