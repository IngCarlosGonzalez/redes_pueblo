<?php

namespace Database\Seeders;

use App\Models\Colonia;
use App\Models\Municipio;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Municipio::create(
            [
                'nombre' => 'ABASOLO',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'ACUÑA',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'ALLENDE',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'ARTEAGA',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'CANDELA',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'CASTAÑOS',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'CUATRO CIENEGAS',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'ESCOBEDO',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'FRANCISCO I. MADERO',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'FRONTERA',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'GENERAL CEPEDA',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'GUERRERO',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'HIDALGO',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'JIMENEZ',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'JUAREZ',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'LA MADRID',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'MATAMOROS',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'MONCLOVA',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'MORELOS',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'MUZQUIZ',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'NADADORES',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'NAVA',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'OCAMPO',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'PARRAS DE LA FUENTE',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'PIEDRAS NEGRAS',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'PROGRESO',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'RAMOS ARIZPE',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'SABINAS',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'SACRAMENTO',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'SALTILLO',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'SAN BUENAVENTURA',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'SAN JUAN DE SABINAS',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'SAN PEDRO DE LAS COLONIAS',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'SIERRA MOJADA',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'TORREON',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'VIESCA',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'VILLA UNION',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'ZARAGOZA',
            ]
        );
        Municipio::create(
            [
                'nombre' => 'INDETERMINADO',
            ]
        );

        Colonia::create(
            [
                'codigo' => 1,
                'entidad' => 5,
                'municipio_id' => 39,
                'nombre_mpio' => 'INDETERMINADO',
                'distrito_fed' => 0,
                'distrito_local' => 0,
                'numero_de_ruta' => 0,
                'seccion' => 0,
                'tipo_seccion' => 'default',
                'tipo_colonia' => 'default',
                'nombre_colonia' => 'INDETERMINADO',
                'cod_post_colon' => 22222,
                'num_control' => 9999999,
            ]
        );
    }
}
