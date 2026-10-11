<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoSubGrupo extends Model
{
    use HasFactory;

    protected $table = 'tipo_sub_grupo';

    protected $primaryKey = 'id_sub_grupo';

    protected $fillable = [
        'grupo_principal_id',
        'sub_grupo_nome',
    ];

    public function grupoPrincipal()
    {
        return $this->belongsTo(
            TipoGrupoPrincipal::class,
            'grupo_principal_id',
            'id_grupo_principal'
        );
    }

    public function categorias()
    {
        return $this->hasMany(
            TipoCategoria::class,
            'sub_grupo_id',
            'id_sub_grupo'
        );
    }
}