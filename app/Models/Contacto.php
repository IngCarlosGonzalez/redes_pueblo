<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contacto extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'nivel_en_red'        => 5,
        'clave_tipo'          => 'Integrante',
        'requerimiento'       => 'n/a',
        'colonia_catalogada'  => true,
        'domicilio_colonia'   => 'n/a',
        'distrito_federal'    => 0,
        'distrito_estatal'    => 0,
        'numero_de_ruta'      => 0,
        'numero_seccion'      => 0,
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

}
