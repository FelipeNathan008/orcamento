
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FluxoCaixa extends Model
{
    use HasFactory;

    protected $table = 'fluxo_caixa';

    protected $primaryKey = 'id_fluxo';

    protected $fillable = [
        'grupo_principal_id',
        'sub_grupo_id',
        'categoria_id',
        'sub_categoria_id',
        'flu_data_despesa',
        'flu_id_movimentacao',
        'conta_bancaria_id',
        'flu_valor',
        'flu_num_doc',
        'flu_desc',
        'flu_tipo_fiscal',
    ];

    public function grupoPrincipal()
    {
        return $this->belongsTo(
            TipoGrupoPrincipal::class,
            'grupo_principal_id',
            'id_grupo_principal'
        );
    }

    public function subGrupo()
    {
        return $this->belongsTo(
            TipoSubGrupo::class,
            'sub_grupo_id',
            'id_sub_grupo'
        );
    }

    public function categoria()
    {
        return $this->belongsTo(
            TipoCategoria::class,
            'categoria_id',
            'id_categoria'
        );
    }

    public function subCategoria()
    {
        return $this->belongsTo(
            TipoSubCategoria::class,
            'sub_categoria_id',
            'id_sub_categoria'
        );
    }

    public function movimentacao()
    {
        return $this->belongsTo(
            Movimentacao::class,
            'flu_id_movimentacao',
            'id_movimentacao'
        );
    }

    public function conta()
    {
        return $this->belongsTo(
            ContaBancaria::class,
            'conta_bancaria_id'
        );
    }
}