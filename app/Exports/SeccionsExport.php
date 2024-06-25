<?php

namespace App\Exports;

use App\Models\Seccion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class SeccionsExport implements FromCollection
{

    protected Collection $secciones;

    public function __construct( )
    {
        $this->secciones = Seccion::where('municipio_id', 5)->get();
    }

    public function collection(): collection
    {
        return $this->secciones;
    }

}
