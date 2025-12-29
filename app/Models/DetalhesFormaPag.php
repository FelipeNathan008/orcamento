<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalhesFormaPag extends Model
{
    protected $table = 'detalhes_forma_pag';
    protected $primaryKey = 'id_det_forma';
    public $timestamps = false;

    protected $fillable = [
        'id_forma_pag',
        'det_forma_valor_parcela',
        'det_forma_data_venc'
    ];

    public function formaPagamento()
    {
        return $this->belongsTo(FormaPagamento::class, 'id_forma_pag', 'id_forma_pag');
    }
}
