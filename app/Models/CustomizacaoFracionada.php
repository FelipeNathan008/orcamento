<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class CustomizacaoFracionada extends Model
{
    use HasFactory;

    protected $table = 'customizacao_fracionada';

    protected $primaryKey = 'id_customizacao_fracionada';

    public $incrementing = true;

    protected $keyType = 'integer';

    protected $fillable = [
        'detalhes_orcamento_fracionado_id',
        'cust_tipo',
        'cust_local',
        'cust_posicao',
        'cust_tamanho',
        'cust_formatacao',
        'cust_descricao',
        'cust_imagem',
        'cust_valor',
    ];

    protected $hidden = [
        'cust_imagem',
    ];

    protected $casts = [
        'cust_valor' => 'decimal:2',
    ];

    public function detalhesOrcamentoFracionado(): BelongsTo
    {
        return $this->belongsTo(DetalhesOrcamentoFracionado::class, 'detalhes_orcamento_fracionado_id', 'id_det_fracionado');
    }
}
