<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customizacao extends Model
{
    use HasFactory;

    protected $table = 'customizacao';

    protected $primaryKey = 'id_customizacao';

    public $incrementing = true;

    protected $keyType = 'integer';

    protected $fillable = [
        'detalhes_orcamento_id_det',
        'cust_tipo',
        'cust_local',
        'cust_posicao',
        'cust_tamanho',
        'cust_formatacao',
        'cust_descricao',
        'cust_imagem',
        'cust_valor',
    ];

    protected $hidden = [
        'cust_imagem',
    ];

    protected $casts = [
        'cust_valor' => 'float',
    ];


    public function detalhesOrcamento(): BelongsTo
    {
        return $this->belongsTo(DetalhesOrcamento::class, 'detalhes_orcamento_id_det', 'id_det');
    }

    public function preco()
    {
        return $this->belongsTo(PrecoCustomizacao::class, 'id_preco', 'id_preco');
    }
}
