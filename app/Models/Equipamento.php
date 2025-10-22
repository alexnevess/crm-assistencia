<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipamento extends Model
{
    use HasFactory;
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'equip_marca',      
        'equip_modelo',     
        'equip_numero_serie',
    ];

    //Um equipamento pertence a uma empresa
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    //Um equipamento pertence a um cliente
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    //Um equipamento pode ter muitas ordens de serviço
    public function ordemServico(): HasMany
    {
        return $this->hasMany(OrdemServico::class);
    }
}
