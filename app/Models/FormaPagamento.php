<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPagamento extends Model
{
    protected $table = 'forma_pagamento';
    protected $primaryKey = 'id_forma_pag';
    public $timestamps = false;

    protected $fillable = [
        'financeiro_id_fin',
        'tipo_pagamento_id_tipo',
        'forma_valor',
        'forma_mes',
        'forma_descricao',
        'forma_prazo',
        'forma_qtd_parcela',
    ];

    public function financeiro()
    {
        return $this->belongsTo(Financeiro::class, 'financeiro_id_fin', 'id_fin');
    }

    public function tipoPagamento()
    {
        return $this->belongsTo(TipoPagamento::class, 'tipo_pagamento_id_tipo', 'id_tipo_pagamento');
    }

    public function detalhes()
    {
        return $this->hasMany(DetalhesFormaPag::class, 'id_forma_pag', 'id_forma_pag');
    }
    
}
