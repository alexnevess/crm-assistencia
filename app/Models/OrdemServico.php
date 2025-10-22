<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    //Uma OS pertence a um cliente
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    //Uma OS pertence a UM Equipamento
    public function equipamento(): BelongsTo
    {
        return $this->belongsTo(Equipamento::class);
    }

    // O Usuário que criou a OS
    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'criador_user_id'); 
    }

    //O Usuário que atualizou a OS por último
    public function atualizador(): BelongsTo
    {
        // Ajuste o nome da chave estrangeira
        return $this->belongsTo(User::class, 'atualizado_por_user_id'); 
    }

    public static function generateNextNumero(int $empresaId): string
    {
        // 1. Encontra a última OS DESSA empresa para definir o próximo ID.
        // O `latest('id')` garante que pegamos o registro mais recente.
        $ultimoOS = self::where('empresa_id', $empresaId)
                             ->latest('id')
                             ->first();
        
        // 2. Determina o próximo número sequencial.
        // Se não houver OSs, começa em 1. Caso contrário, usa o ID do último registro + 1.
        $novoNumeroSeq = $ultimoOS ? (int)$ultimoOS->id + 1 : 1;

        // 3. Formata o número (Ex: 1 -> 0001)
        $numeroFormatado = str_pad($novoNumeroSeq, 4, '0', STR_PAD_LEFT);
        
        // 4. Concatena com o ano atual (Ex: 0001/2025)
        return "{$numeroFormatado}/" . date('Y');
    }

}
