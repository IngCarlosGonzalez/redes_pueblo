<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

}
