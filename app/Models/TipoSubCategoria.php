<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoSubCategoria extends Model
{
    use HasFactory;

    protected $table = 'tipo_sub_categoria';

    protected $primaryKey = 'id_sub_categoria';

    protected $fillable = [
        'categoria_id',
        'sub_categoria_nome',
    ];

    public function categoria()
    {
        return $this->belongsTo(
            TipoCategoria::class,
            'categoria_id',
            'id_categoria'
        );
    }
}