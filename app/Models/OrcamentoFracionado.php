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
        'orc_desconto_tipo',
        'orc_desconto_valor',
        'orc_desconto_motivo',
    ];

    protected $casts = [
        'orc_data_inicio' => 'date',
        'orc_data_fim' => 'date',
        'orc_desconto_valor' => 'decimal:2',
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

            // Valor dos produtos
            $total +=
                $quantidade *
                (float) ($detalhe->det_valor_unit ?? 0);

            // Valor das customizações
            foreach ($detalhe->customizacoes as $customizacao) {

                $total +=
                    $quantidade *
                    (float) ($customizacao->cust_valor ?? 0);
            }
        }

        return $total;
    }

    public function getValorDescontoAttribute()
    {
        $totalBruto = $this->total_bruto;

        if ($this->orc_desconto_tipo === 'percentual') {

            return round(
                $totalBruto *
                ((float) $this->orc_desconto_valor / 100),
                2
            );
        }

        if ($this->orc_desconto_tipo === 'valor') {

            return min(
                (float) $this->orc_desconto_valor,
                $totalBruto
            );
        }

        return 0;
    }

    public function getTotalComDescontoAttribute()
    {
        return $this->total_bruto - $this->valor_desconto;
    }
}