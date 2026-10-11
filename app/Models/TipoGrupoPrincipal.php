<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoGrupoPrincipal extends Model
{
    use HasFactory;

    protected $table = 'tipo_grupo_principal';

    protected $primaryKey = 'id_grupo_principal';

    protected $fillable = [
        'grupo_principal_nome',
    ];

    public function subGrupos()
    {
        return $this->hasMany(
            TipoSubGrupo::class,
            'grupo_principal_id',
            'id_grupo_principal'
        );
    }
}