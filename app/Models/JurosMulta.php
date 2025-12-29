<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurosMulta extends Model
{
    use HasFactory;

    protected $table = 'juros_multa';

    protected $primaryKey = 'id_juros_multa';

    protected $fillable = [
        'indice_juros',
        'indice_multa',
    ];

    protected $casts = [
        'indice_juros' => 'float',
        'indice_multa' => 'float',
    ];
}
