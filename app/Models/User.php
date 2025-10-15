<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use App\Models\Empresa;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'perfil_acesso',
        'password',
    ];

    public function empresaCriada(): HasOne
    {
        // Procura a FK 'user_id' na tabela 'empresas'
        return $this->hasOne(Empresa::class, 'user_id'); 
    }

    public function empresaAfiliada(): BelongsTo 
    {
        // Usa a FK 'empresa_id' na própria tabela 'users'
        return $this->belongsTo(Empresa::class, 'empresa_id'); 
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
