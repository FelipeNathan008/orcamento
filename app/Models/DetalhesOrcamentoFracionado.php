<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DetalhesOrcamentoFracionado extends Model
{
    use HasFactory;
    protected $table = 'detalhes_orcamento_fracionado';
    protected $primaryKey = 'id_det_fracionado';
    public $incrementing = true;
    protected $keyType = 'integer';
    protected $fillable = [

        'orcamento_fracionado_id',

        'detalhes_orcamento_id_det',

        'orcamento_cliente_orcamento_id_co',
        'orcamento_cliente_id_cliente',

        'produto_id_produto',

        'det_cod',
        'det_categoria',
        'det_modelo',
        'det_cor',
        'det_tamanho',
        'det_quantidade',
        'det_valor_unit',
        'det_genero',
        'det_caract',
        'det_observacao',
        'det_anotacao',
    ];


    protected $casts = [
        'det_valor_unit' => 'decimal:2',
        'det_quantidade' => 'integer',
    ];

    public function orcamentoFracionado(): BelongsTo
    {
        return $this->belongsTo(
            OrcamentoFracionado::class,
            'orcamento_fracionado_id',
            'id_orcamento_fracionado'
        );
    }

    public function detalheOriginal(): BelongsTo
    {
        return $this->belongsTo(
            DetalhesOrcamento::class,
            'detalhes_orcamento_id_det',
            'id_det'
        );
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(
            Produto::class,
            'produto_id_produto',
            'id_produto'
        );
    }

    public function clienteOrcamento(): BelongsTo
    {
        return $this->belongsTo(
            ClienteOrcamento::class,
            'orcamento_cliente_orcamento_id_co',
            'id_co'
        );
    }

    public function customizacoes(): HasMany
    {
        return $this->hasMany(
            CustomizacaoFracionada::class,
            'detalhes_orcamento_fracionado_id',
            'id_det_fracionado'
        );
    }
}
