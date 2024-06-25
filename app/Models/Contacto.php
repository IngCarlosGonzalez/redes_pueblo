<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
