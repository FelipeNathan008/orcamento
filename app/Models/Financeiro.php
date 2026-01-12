<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Financeiro extends Model
{
    protected $table = 'financeiro';
    protected $primaryKey = 'id_fin';
    public $timestamps = true;

    protected $fillable = [
        'orcamento_id_orcamento',
        'id_orcamento',
        'id_cliente',
        'fin_nome_cliente',
        'fin_valor_total',
        'fin_status'
    ];

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class, 'orcamento_id_orcamento', 'id_orcamento');
    }
    public function logs()
    {
        return $this->hasMany(\App\Models\LogStatus::class, 'log_id_orcamento', 'orcamento_id_orcamento');
    }
    public function formasPagamento()
    {
        return $this->hasMany(FormaPagamento::class, 'financeiro_id_fin', 'id_fin');
    }
}
