<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusMercadoria extends Model
{
    protected $table = 'status_mercadoria';
    protected $primaryKey = 'id_status_merc';
    public $timestamps = false;

    protected $fillable = [
        'status_merc_nome'
    ];

    public function logs()
    {
        return $this->hasMany(LogStatus::class, 'status_mercadoria_id_status', 'id_status_merc');
    }
}
