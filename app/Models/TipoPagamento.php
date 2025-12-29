<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoPagamento extends Model
{
    protected $table = 'tipo_pagamento';
    protected $primaryKey = 'id_tipo_pagamento';
    public $timestamps = false;

    protected $fillable = [
        'tipo_plano_fin'
    ];
    public function formasPagamento()
    {
        return $this->hasMany(FormaPagamento::class, 'tipo_pagamento_id_tipo', 'id_tipo_pagamento');
    }
    public function cobrancas()
    {
        return $this->hasMany(Cobranca::class, 'cobr_id_tipo', 'id_tipo_pagamento');
    }
}
