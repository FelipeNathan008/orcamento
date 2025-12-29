<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customizacao extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customizacao';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_customizacao';

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
        'detalhes_orcamento_id_det',
        'cust_tipo',
        'cust_local',
        'cust_posicao',
        'cust_tamanho',
        'cust_formatacao',
        'cust_descricao',
        'cust_imagem',
        'cust_valor', // Adicionado o novo campo de valor
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'cust_imagem',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'cust_imagem' => 'binary', // Removido pois não é necessário
        'cust_valor' => 'float', // Adicionado para garantir que o valor seja tratado como float
    ];

    /**
     * Get the DetalhesOrcamento that owns the Customizacao.
     */
    public function detalhesOrcamento(): BelongsTo
    {
        return $this->belongsTo(DetalhesOrcamento::class, 'detalhes_orcamento_id_det', 'id_det');
    }

    /**
     * Get the PrecoCustomizacao associated with the Customizacao.
     * Este relacionamento é um exemplo e pode não estar sendo usado
     * dependendo da lógica do seu sistema.
     */
    public function preco()
    {
        return $this->belongsTo(PrecoCustomizacao::class, 'id_preco', 'id_preco');
    }
}
