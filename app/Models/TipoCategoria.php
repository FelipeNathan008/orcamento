<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCategoria extends Model
{
    use HasFactory;

    protected $table = 'tipo_categoria';

    protected $primaryKey = 'id_categoria';

    protected $fillable = [
        'sub_grupo_id',
        'categoria_nome',
    ];

    public function subGrupo()
    {
        return $this->belongsTo(
            TipoSubGrupo::class,
            'sub_grupo_id',
            'id_sub_grupo'
        );
    }

    public function subCategorias()
    {
        return $this->hasMany(
            TipoSubCategoria::class,
            'categoria_id',
            'id_categoria'
        );
    }
}