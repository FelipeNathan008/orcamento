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
        'nota_numero',
        'nota_id_tipo',
        'nota_data',
        'nota_id_movimentacao',
        'nota_valor',
        'nota_desc'
    ];

    protected $casts = [
        'nota_data' => 'date',
        'nota_valor' => 'decimal:2',
    ];

    // RELACIONAMENTOS

    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class, 'orcamento_id_orcamento', 'id_orcamento');
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoFluxoCaixa::class, 'nota_id_tipo', 'id_tipo_fluxo');
    }

    public function movimentacao(): BelongsTo
    {
        return $this->belongsTo(Movimentacao::class, 'nota_id_movimentacao', 'id_movimentacao');
    }
}