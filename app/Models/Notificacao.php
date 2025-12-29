<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    protected $table = 'notificacao';
    protected $primaryKey = 'id_notificacao';

    protected $fillable = [
        'cobranca_id_cobranca',
        'not_tipo',
        'not_descricao'
    ];

    public function cobranca()
    {
        return $this->belongsTo(Cobranca::class, 'cobranca_id_cobranca', 'id_cobranca');
    }
    public function getTipoNomeAttribute()
    {
        return match ($this->not_tipo) {
            1 => 'Aviso Bancário',
            2 => 'E-mail / Telefone',
            3 => 'Carta Registrada',
            4 => 'Protesto',
            default => 'Não informado',
        };
    }
}
