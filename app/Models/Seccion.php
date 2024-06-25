<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seccion extends Model
{
    use HasFactory;

    protected $fillable = [
        'seccion_id',
        'municipio_id',
        'numero_ruta',
        'distrito_fed',
        'distrito_loc',
    ];

    public function municipio(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }

}
