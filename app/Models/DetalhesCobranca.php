<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalhesCobranca extends Model
{
    protected $table = 'detalhes_cobranca';
    protected $primaryKey = 'id_det_cobranca';
    public $timestamps = false;

    protected $fillable = [
        'cobranca_id',
        'det_cobr_valor_parcela',
        'det_cobr_data_venc',
        'det_cobr_status',
        'id_det_forma'
    ];

    public function cobranca()
    {
        return $this->belongsTo(Cobranca::class, 'cobranca_id', 'id_cobranca');
    }
}
