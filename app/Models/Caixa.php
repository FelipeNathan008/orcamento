<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caixa extends Model
{
    protected $table = 'caixa';

    protected $primaryKey = 'id_caixa';

    protected $fillable = [
        'caixa_saldo'
    ];

    public $timestamps = true;
}