<?php

namespace App\Imports;

use App\Models\Seccion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SeccionsImport implements ToModel, WithUpserts, WithChunkReading, WithBatchInserts, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Seccion([
            'seccion_id'    => $row['seccion'],
            'municipio_id'  => $row['municipio'],
            'numero_ruta'   => $row['ruta'],
            'distrito_fed'  => $row['dist_fed'],
            'distrito_loc'  => $row['dist_loc'],
        ]);
    }

    public function uniqueBy()
    {
        return 'seccion_id';
    }
            
    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }

}
