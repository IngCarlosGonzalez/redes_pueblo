<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filtrarcol extends Model
{
    use HasFactory;

    protected $fillable = [
        'ejecutor',
        'colonia_id',
        'municipio_id',
        'num_seccion',
        'nombre_colonia',
        'nombre_mpio',
        'descripcion',
    ];
    
}
