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
        'orc_cod_interno'
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
}
