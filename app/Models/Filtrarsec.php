<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filtrarsec extends Model
{
    use HasFactory;

    protected $fillable = [
        'ejecutor',
        'seccion',
        'municipio_id',
        'nombre_mpio',
        'descripcion',
    ];

}
