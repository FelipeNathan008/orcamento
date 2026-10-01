<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    protected $table = 'notificacao';
    protected $primaryKey = 'id_notificacao';
    public $timestamps = false;

    protected $fillable = [
        'id_det_forma',
        'not_tipo',
        'not_descricao'
    ];

    public function detalheFormaPag()
    {
        return $this->belongsTo(
            DetalhesFormaPag::class,
            'id_det_forma',
            'id_det_forma'
        );
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