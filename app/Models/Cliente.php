<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'empresa_id',
        'nome',
        'telefone',
        'email',
        'cnpj_cpf',
    ];

    //Um cliente pertence a uma empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    //Um Cliente pode ter muitos equipamentos
    public function equipamento()
    {
        return $this->hasmany(Equipamento::class);
    }

    //Um Cliente pode ter muitas ordens de serviçod
    public function ordensServico()
    {
        return $this->hasmany(OrdemServico::Class);
    }
}
