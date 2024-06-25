<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Municipio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre'
    ];

    public function colonias(): HasMany
    {
        return $this->hasMany(Colonia::class);
    }

    public function contactos(): HasMany
    {
        return $this->hasMany(Contacto::class);
    }

    public function secciones(): HasMany
    {
        return $this->hasMany(Seccion::class);
    }
    
}
