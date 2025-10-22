<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'nome',
        'telefone',
        'email',
        'cnpj_cpf',
    ];

    //Um cliente pertence a uma empresa
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    //Um Cliente pode ter muitos equipamentos
    public function equipamento(): hasMany
    {
        return $this->hasMany(Equipamento::class);
    }

    //Um Cliente pode ter muitas ordens de serviçod
    public function ordensServico(): HasMany
    {
        return $this->hasMany(OrdemServico::Class);
    }
}
