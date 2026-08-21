<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteOrcamento extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cliente_orcamento';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_co';

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
        'clie_orc_nome',
        'clie_orc_email',
        'clie_orc_telefone',
        'clie_orc_celular',
        'clie_orc_tipo_doc',
        'clie_orc_cnpj',
        'clie_orc_cpf',
        'clie_orc_logradouro',
        'clie_orc_bairro',
        'clie_orc_cep',
        'clie_orc_cidade',
        'clie_orc_uf',
        'clie_orc_cod_interno',
        'clie_orc_ie',
    ];

    
    protected $casts = [

    ];
    public function orcamentos()
    {
        return $this->hasMany(Orcamento::class, 'cliente_orcamento_id_co', 'id_co');
    }

    public function contatos()
    {
        return $this->hasMany(ContatoCliente::class, 'cliente_orcamento_id_co', 'id_co');
    }
}
