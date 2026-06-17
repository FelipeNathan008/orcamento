<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaldoConta extends Model
{
    protected $table = 'saldo_conta';

    protected $primaryKey = 'id_saldo_conta';

    protected $fillable = [
        'id_conta_bancaria_id',
        'saldo_conta_valor',
    ];

    // RELACIONAMENTO
    public function contaBancaria()
    {
        return $this->belongsTo(
            ContaBancaria::class,
            'id_conta_bancaria_id',
            'id_conta'
        );
    }
}