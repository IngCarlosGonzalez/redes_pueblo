<?php

namespace Database\Seeders;

use App\Models\Contacto;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 04,
            'colonia_id'          => 4606,
            'numero_seccion'      => 57,
            'domicilio_colonia'   => 'SANTA ELENA',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 04,
            'colonia_id'          => 4614,
            'numero_seccion'      => 57,
            'domicilio_colonia'   => 'VILLALBA',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 04,
            'colonia_id'          => 4616,
            'numero_seccion'      => 57,
            'domicilio_colonia'   => 'ZONA CENTRO',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 04,
            'colonia_id'          => 4618,
            'numero_seccion'      => 58,
            'domicilio_colonia'   => 'JARDIN',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 04,
            'colonia_id'          => 4621,
            'numero_seccion'      => 58,
            'domicilio_colonia'   => 'SAN ISIDRO',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 04,
            'colonia_id'          => 4625,
            'numero_seccion'      => 58,
            'domicilio_colonia'   => 'ZONA CENTRO',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 04,
            'colonia_id'          => 4636,
            'numero_seccion'      => 59,
            'domicilio_colonia'   => 'SAN FRANCISCO',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 04,
            'colonia_id'          => 4637,
            'numero_seccion'      => 59,
            'domicilio_colonia'   => 'SAN ISIDRO',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 04,
            'colonia_id'          => 4638,
            'numero_seccion'      => 59,
            'domicilio_colonia'   => 'VALLE DE ARTEAGA',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 04,
            'colonia_id'          => 4639,
            'numero_seccion'      => 59,
            'domicilio_colonia'   => 'ZONA CENTRO',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 27,
            'colonia_id'          => 4943,
            'numero_seccion'      => 652,
            'domicilio_colonia'   => 'ANACAHUITA',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 27,
            'colonia_id'          => 4947,
            'numero_seccion'      => 652,
            'domicilio_colonia'   => 'EL BARATILLO',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 27,
            'colonia_id'          => 4961,
            'numero_seccion'      => 652,
            'domicilio_colonia'   => 'PORTAL LAS PALOMAS',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 27,
            'colonia_id'          => 4964,
            'numero_seccion'      => 652,
            'domicilio_colonia'   => 'ZONA CENTRO',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 27,
            'colonia_id'          => 4965,
            'numero_seccion'      => 654,
            'domicilio_colonia'   => 'ANACAHUITA',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 27,
            'colonia_id'          => 4966,
            'numero_seccion'      => 654,
            'domicilio_colonia'   => 'JARDIN',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 27,
            'colonia_id'          => 4970,
            'numero_seccion'      => 654,
            'domicilio_colonia'   => 'ZONA CENTRO',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 27,
            'colonia_id'          => 4979,
            'numero_seccion'      => 656,
            'domicilio_colonia'   => 'GUANAJUATO DE ABAJO',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 27,
            'colonia_id'          => 4980,
            'numero_seccion'      => 656,
            'domicilio_colonia'   => 'GUANAJUATO DE ARRIBA',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 27,
            'colonia_id'          => 4985,
            'numero_seccion'      => 656,
            'domicilio_colonia'   => 'ZONA CENTRO',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 30,
            'colonia_id'          => 2192,
            'numero_seccion'      => 730,
            'domicilio_colonia'   => 'JARDINES COLONIALES',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 30,
            'colonia_id'          => 2193,
            'numero_seccion'      => 730,
            'domicilio_colonia'   => 'LAS BRISAS',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 30,
            'colonia_id'          => 2196,
            'numero_seccion'      => 731,
            'domicilio_colonia'   => 'BRISAS PONIENTE',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 30,
            'colonia_id'          => 2202,
            'numero_seccion'      => 731,
            'domicilio_colonia'   => 'JARDINES COLONIALES',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 30,
            'colonia_id'          => 2214,
            'numero_seccion'      => 731,
            'domicilio_colonia'   => 'LOS GONZALEZ',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 30,
            'colonia_id'          => 2223,
            'numero_seccion'      => 731,
            'domicilio_colonia'   => 'PUERTA DEL SOL',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 30,
            'colonia_id'          => 2242,
            'numero_seccion'      => 733,
            'domicilio_colonia'   => 'LOS RODRIGUEZ',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 30,
            'colonia_id'          => 2243,
            'numero_seccion'      => 733,
            'domicilio_colonia'   => 'RANCHO DE PENA',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 30,
            'colonia_id'          => 2256,
            'numero_seccion'      => 735,
            'domicilio_colonia'   => 'VALLE LAS PALMAS',
        ]);
        Contacto::factory(10)->create([
            'colonia_catalogada'  => 1,
            'municipio_id'        => 30,
            'colonia_id'          => 2257,
            'numero_seccion'      => 735,
            'domicilio_colonia'   => 'VIRREYES OBRERA',
        ]); */
    }
}
