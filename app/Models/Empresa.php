<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';          // Nome da tabela
    protected $primaryKey = 'id_emp';      // Chave primária

    protected $fillable = [
        'emp_nome',
        'emp_cnpj',
        'emp_logradouro',
        'emp_bairro',
        'emp_cidade',
        'emp_uf',
        'emp_cep',
    ];

    // public $timestamps = false; // Se quiser desabilitar created_at/updated_at
}
