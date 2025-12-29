<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Importa os modelos relacionados para que possam ser referenciados
use App\Models\Orcamento;
use App\Models\Produto;
use App\Models\ClienteOrcamento;
use App\Models\Customizacao; // Adicionando o modelo Customizacao

class DetalhesOrcamento extends Model
{
    use HasFactory;

    // Define o nome da tabela no banco de dados, conforme sua definição SQL.
    protected $table = 'detalhes_orcamento';

    // Define a chave primária da tabela.
    protected $primaryKey = 'id_det';

    // Indica se a chave primária é auto-incrementável (é um INT NOT NULL AUTO_INCREMENT).
    public $incrementing = true;

    // Define quais atributos são "mass assignable" (podem ser preenchidos via create ou update).
    protected $fillable = [
        'orcamento_id_orcamento',
        'orcamento_cliente_orcamento_id_co',
        'produto_id_produto',
        'det_cod',
        'orcamento_cliente_id_cliente',
        'det_categoria',
        'det_modelo',
        'det_cor',
        'det_tamanho',
        'det_quantidade',
        'det_valor_unit',
        'det_genero',
        'det_caract',
        'det_observacao',
        'det_anotacao',
    ];

    // Define os atributos que devem ser convertidos para tipos de dados específicos.
    protected $casts = [
        'det_valor_unit' => 'decimal:2',
        'orcamento_id_orcamento' => 'integer',
        'orcamento_cliente_orcamento_id_co' => 'integer',
        'produto_id_produto' => 'integer',
        'orcamento_cliente_id_cliente' => 'integer',
        'det_quantidade' => 'integer',
    ];

    /**
     * Define o relacionamento muitos-para-um com o modelo Produto.
     * Um DetalhesOrcamento pertence a um Produto.
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id_produto', 'id_produto');
    }

    /**
     * Define o relacionamento muitos-para-um com o modelo Orcamento.
     * Um DetalhesOrcamento pertence a um Orcamento.
     */
    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class, 'orcamento_id_orcamento', 'id_orcamento');
    }

    /**
     * Define o relacionamento muitos-para-um com o modelo ClienteOrcamento.
     */
    public function clienteOrcamento()
    {
        return $this->belongsTo(ClienteOrcamento::class, 'orcamento_cliente_orcamento_id_co', 'id_co');
    }

    /**
     * Define o relacionamento de um para muitos com o modelo Customizacao.
     * Um DetalhesOrcamento TEM MUITAS Customizacoes.
     * A chave estrangeira na tabela de customizações é 'detalhes_orcamento_id_det'.
     */
    public function customizacoes()
    {
        return $this->hasMany(Customizacao::class, 'detalhes_orcamento_id_det', 'id_det');
    }
}
