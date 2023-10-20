<?php

namespace App\Models;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
        'requerimiento'       => '',
        'colonia_catalogada'  => false,
        'domicilio_colonia'   => '',
        'distrito_federal'    => 0,
        'distrito_estatal'    => 0,
        'numero_de_ruta'      => 0,
        'numero_seccion'      => 0,
        'con_domi_actual'     => false,
        'datos_verificados'   => false,
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 640, 360)
            ->nonQueued();
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'mensaje',
        'cadenita',
    ];

    public function getMensajeAttribute()
    {
        return '->';
    }

    public function getCadenitaAttribute()
    {
        return 'holi';
    }

    /**
     * Scope con query para solo accesar regs con nivel mayor que...
     */
    public function scopeOfNivel(Builder $query, string $mayorque): void
    {
        $query->where('nivel_en_red', '>', $mayorque);
    }

}
