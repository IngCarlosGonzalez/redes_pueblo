<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_mpio'
    ];

    public function colonias()
    {
        return $this->hasMany('App\Models\Colonia');
    }

}
