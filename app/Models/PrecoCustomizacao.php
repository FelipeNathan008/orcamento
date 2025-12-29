<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrecoCustomizacao extends Model
{
    protected $table = 'preco_customizacao';
    protected $primaryKey = 'id_preco';
    public $timestamps = false;

    protected $fillable = [
        'preco_tipo',
        'preco_tamanho',
        'preco_valor',
    ];
}