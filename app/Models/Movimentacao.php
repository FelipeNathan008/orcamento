<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimentacao extends Model
{
    protected $table = 'movimentacao';
    protected $primaryKey = 'id_movimentacao';
    public $timestamps = false;

    protected $fillable = [
        'mov_nome',
    ];
}