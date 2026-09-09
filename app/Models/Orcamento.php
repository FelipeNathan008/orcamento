<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importe o BelongsTo

class Orcamento extends Model
{
    use HasFactory;
    protected $table = 'orcamento';
    protected $primaryKey = 'id_orcamento';

    public $incrementing = true;

    protected $keyType = 'integer';

    protected $fillable = [
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
        'orc_anotacao_espec' => 'string',
        'orc_anotacao_geral' => 'string',
        'orc_motivo_rejeicao' => 'string',
        'orc_cod_fabrica' => 'string',
        'orc_cod_interno' => 'string',
    ];


    public function clienteOrcamento(): BelongsTo
    {
        return $this->belongsTo(ClienteOrcamento::class, 'cliente_orcamento_id_co', 'id_co');
    }

    public function detalhesOrcamento()
    {
        return $this->hasMany(DetalhesOrcamento::class, 'orcamento_id_orcamento', 'id_orcamento');
    }

    public function fracionados()
    {
        return $this->hasMany(OrcamentoFracionado::class, 'orcamento_id_orcamento', 'id_orcamento');
    }

    public function getTotalBrutoAttribute()
    {
        $total = 0;

        foreach ($this->detalhesOrcamento as $detalhe) {
            $quantidade = (int) ($detalhe->det_quantidade ?? 0);

            $subtotal = $quantidade * (float) ($detalhe->det_valor_unit ?? 0);

            foreach ($detalhe->customizacoes as $customizacao) {
                $subtotal += $quantidade * (float) ($customizacao->cust_valor ?? 0);
            }

            $total += $subtotal;
        }

        return $total;
    }

    public function getValorDescontoAttribute()
    {
        $totalBruto = $this->total_bruto;

        if ($this->orc_desconto_tipo === 'percentual') {
            return round($totalBruto * ($this->orc_desconto_valor / 100), 2);
        }

        if ($this->orc_desconto_tipo === 'valor') {
            // nunca deixa o desconto passar do total bruto
            return min($this->orc_desconto_valor, $totalBruto);
        }

        return 0;
    }

    public function getTotalComDescontoAttribute()
    {
        return $this->total_bruto - $this->valor_desconto;
    }
}
