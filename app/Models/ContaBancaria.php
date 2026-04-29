<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContaBancaria extends Model
{
    protected $table = 'conta_bancaria';

    protected $primaryKey = 'id_conta';

    protected $fillable = [
        'conta_nome_banco',
        'conta_cod_banco',
        'conta_agencia',
        'numero_conta_corrente',
        'numero_digito_corrente',
        'conta_desc',
    ];
    public function formasPagamento()
    {
        return $this->hasMany(FormaPagamento::class, 'conta_bancaria_id');
    }

    public function fluxos()
    {
        return $this->hasMany(FluxoCaixa::class, 'conta_bancaria_id');
    }
}
