<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrcamentoFracionado extends Model
{
    use HasFactory;

    protected $table = 'orcamento_fracionado';

    protected $primaryKey = 'id_orcamento_fracionado';

    public $incrementing = true;

    protected $keyType = 'integer';

    protected $fillable = [
        'orcamento_id_orcamento',
        'orc_fracao',
        'cliente_orcamento_id_co',
        'orc_data_inicio',
        'orc_data_fim',
        'orc_status',
        'orc_anotacao_espec',
        'orc_anotacao_geral',
        'orc_motivo_rejeicao',
        'orc_cod_fabrica',
        'orc_cod_interno',
    ];

    protected $casts = [
        'orc_data_inicio' => 'date',
        'orc_data_fim' => 'date',
        'orc_fracao' => 'integer',
    ];

    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(
            Orcamento::class,
            'orcamento_id_orcamento',
            'id_orcamento'
        );
    }

    public function clienteOrcamento(): BelongsTo
    {
        return $this->belongsTo(
            ClienteOrcamento::class,
            'cliente_orcamento_id_co',
            'id_co'
        );
    }

    public function detalhesOrcamentoFracionado(): HasMany
    {
        return $this->hasMany(
            DetalhesOrcamentoFracionado::class,
            'orcamento_fracionado_id',
            'id_orcamento_fracionado'
        );
    }

    public function getTotalBrutoAttribute()
    {
        $total = 0;

        foreach ($this->detalhesOrcamentoFracionado as $detalhe) {

            $quantidade = (int) ($detalhe->det_quantidade ?? 0);

            $total +=
                $quantidade *
                (float) ($detalhe->det_valor_unit ?? 0);

            foreach ($detalhe->customizacoes as $customizacao) {

                $total +=
                    $quantidade *
                    (float) ($customizacao->cust_valor ?? 0);
            }
        }

        return round($total, 2);
    }
}
