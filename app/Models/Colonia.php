<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colonia extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'entidad',
        'municipio_id',
        'nombre_mpio',
        'distrito_fed',
        'distrito_local',
        'numero_de_ruta',
        'seccion',
        'tipo_seccion',
        'tipo_colonia',
        'nombre_colonia',
        'cod_post_colon',
        'num_control'
    ];

    public function municipio()
    {
        return $this->belongsTo('App\Models\Municipio')->withDefault();
    }

}
