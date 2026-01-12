<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogStatus extends Model
{
    protected $table = 'log_status';
    protected $primaryKey = 'id_log_status';
    public $timestamps = true;

    protected $fillable = [
        'status_mercadoria_id_status',
        'log_id_orcamento',
        'log_id_cliente',
        'log_nome_status',
        'log_situacao'
    ];

    public function status()
    {
        return $this->belongsTo(StatusMercadoria::class, 'status_mercadoria_id_status', 'id_status_merc');
    }
    
}
