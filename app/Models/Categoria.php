<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre'
    ];

    public function patients(): HasMany
    {
        return $this->hasMany(Contacto::class);
    }
}
