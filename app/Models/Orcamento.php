<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importe o BelongsTo

class Orcamento extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orcamento';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_orcamento';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The data type of the primary key.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cliente_orcamento_id_co',
        'orc_data_inicio',
        'orc_data_fim',
        'orc_status',
        'orc_anotacao_espec', // novo campo
        'orc_anotacao_geral', // novo campo
        'orc_motivo_rejeicao'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'orc_data_inicio' => 'date',
        'orc_data_fim' => 'date',
        'orc_anotacao_espec' => 'string',
        'orc_anotacao_geral' => 'string',
        'orc_motivo_rejeicao' => 'string',
    ];

    /**
     * Get the ClienteOrcamento that owns the Orcamento.
     */
    public function clienteOrcamento(): BelongsTo
    {
        return $this->belongsTo(ClienteOrcamento::class, 'cliente_orcamento_id_co', 'id_co');
    }

    public function detalhesOrcamento()
    {
        return $this->hasMany(DetalhesOrcamento::class, 'orcamento_id_orcamento', 'id_orcamento');
    }
}

