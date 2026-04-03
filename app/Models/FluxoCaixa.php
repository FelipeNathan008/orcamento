<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FluxoCaixa extends Model
{
    use HasFactory;

    protected $table = 'fluxo_caixa';
    protected $primaryKey = 'id_fluxo';

    protected $fillable = [
        'flu_data_despesa',
        'flu_id_tipo',
        'flu_id_movimentacao',
        'flu_valor',
        'flu_num_doc',
        'flu_desc'
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoFluxoCaixa::class, 'flu_id_tipo', 'id_tipo_fluxo');
    }

    public function movimentacao()
    {
        return $this->belongsTo(Movimentacao::class, 'flu_id_movimentacao', 'id_movimentacao');
    }
}