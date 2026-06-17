<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produto'; 

    protected $primaryKey = 'id_produto';

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


    protected $casts = [
        'prod_tamanho' => 'array', 
        'prod_caract' => 'string', 
        'prod_preco' => 'float', 
    ];

}
