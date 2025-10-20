<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'equip_marca',      
        'equip_modelo',     
        'equip_numero_serie',
    ];

    //Um equipamento pertence a uma empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    //Um equipamento pertence a um cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    //Um equipamento pode ter muitas ordens de serviço
    public function ordemServico()
    {
        return $this->hasMany(OrdemServico::class);
    }
}
