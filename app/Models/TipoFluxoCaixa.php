<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoFluxoCaixa extends Model
{
    use HasFactory;

    protected $table = 'tipo_fluxo_caixa';
    protected $primaryKey = 'id_tipo_fluxo';
    public $timestamps = false;

    protected $fillable = [
        'tipo_flu_nome',
        'tipo_despesa',
        'tipo_desc'
    ];
}
