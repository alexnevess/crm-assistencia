<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
     'nome',
     'user_id',
    ];

    public function criado_por()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function funcionarios(): HasMany 
    {
        return $this->hasMany(User::class, 'empresa_id');
    }
}