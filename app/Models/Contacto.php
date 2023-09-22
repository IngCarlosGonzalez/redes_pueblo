<?php

namespace App\Models;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Contacto extends Model implements HasMedia
{
    use HasFactory;

    use InteractsWithMedia;

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
        'con_domi_actual'     => false,
    ];

    protected $casts = [
        'fotos_del_ine' => 'array',
        'nombres_reales' => 'array',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class);
    }

    public function registerMediaConversions(Media $media = null): void
{
    $this
        ->addMediaConversion('preview')
        ->fit(Manipulations::FIT_CROP, 300, 300)
        ->nonQueued();
}
}
