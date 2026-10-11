<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotaFiscal extends Model
{
    protected $table = 'nota_fiscal';

    protected $primaryKey = 'id_nota_fiscal';

    public $timestamps = false;

    protected $fillable = [
        'orcamento_id_orcamento',
        'orcamento_id_orcamento_frac',
        'nota_numero',
        'nota_data_emissao',
        'nota_id_movimentacao',
        'nota_valor',
        'nota_desc',
    ];

    protected $casts = [
        'nota_data_emissao' => 'date',
        'nota_valor' => 'decimal:2',
    ];

    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(
            Orcamento::class,
            'orcamento_id_orcamento',
            'id_orcamento'
        );
    }

    public function orcamentoFracionado(): BelongsTo
    {
        return $this->belongsTo(
            OrcamentoFracionado::class,
            'orcamento_id_orcamento_frac',
            'id_orcamento_fracionado'
        );
    }

    public function movimentacao(): BelongsTo
    {
        return $this->belongsTo(
            Movimentacao::class,
            'nota_id_movimentacao',
            'id_movimentacao'
        );
    }
}