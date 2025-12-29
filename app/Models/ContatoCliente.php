<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContatoCliente extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contato_cliente';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_contato';

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
        'cont_nome',
        'cont_telefone',
        'cont_celular',
        'cont_email',
        'cont_tipo',
        'cont_descricao',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Adicione casts aqui se houver campos de data, JSON, etc.
    ];

    /**
     * Get the ClienteOrcamento that owns the ContatoCliente.
     */
    public function clienteOrcamento(): BelongsTo
    {
        return $this->belongsTo(ClienteOrcamento::class, 'cliente_orcamento_id_co', 'id_co');
    }
}

