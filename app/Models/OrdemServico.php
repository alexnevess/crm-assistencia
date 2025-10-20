<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemServico extends Model
{
    use HasFactory;

    protected $table = 'ordens_servico'; // Garante a conexão com a tabela

    protected $fillable = [
        //Chaves de vínculo
        'empresa_id',
        'cliente_id',
        'equipamento_id',
        'criador_user_id',
        'atualizado_por_user_id',

        //Dados da OS
        'status',
        'numero',
        'problema_relato',
        'diagnostico',
        'resolucao',
        'prazo_previsto',
        'finalizado_em',
    ];

    //Uma OS pertence a uma empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    //Uma OS pertence a um cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    //Uma OS pertence a UM Equipamento
    public function equipamento()
    {
        return $this->belongsTo(Equipamento::class);
    }

    // O Usuário que criou a OS
    public function criador()
    {
        return $this->belongsTo(User::class, 'user_id'); 
    }

    //O Usuário que atualizou a OS por último
    public function atualizador()
    {
        // Ajuste o nome da chave estrangeira
        return $this->belongsTo(User::class, 'user_id'); 
    }

}
