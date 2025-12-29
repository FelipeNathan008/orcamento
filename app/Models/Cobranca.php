<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cobranca extends Model
{
    protected $table = 'cobranca';
    protected $primaryKey = 'id_cobranca';
    public $timestamps = false;

    protected $fillable = [
        'cobr_cliente',
        'cobr_id_fin',
        'cobr_id_orc',
        'cobr_id_tipo',
        'cobr_status'
    ];
    public function tipoPagamento()
    {
        return $this->belongsTo(TipoPagamento::class, 'cobr_id_tipo', 'id_tipo_pagamento');
    }
    public function detalhesCobranca()
    {
        return $this->hasMany(DetalhesCobranca::class, 'cobranca_id', 'id_cobranca');
    }
    public function notificacoes()
    {
        return $this->hasMany(Notificacao::class, 'cobranca_id_cobranca');
    }
}
