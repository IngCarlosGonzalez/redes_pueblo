<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categoria::create(
            [
                'nombre' => 'GENERAL',
            ]
        );
        Categoria::create(
            [
                'nombre' => 'AMISTADES',
            ]
        );
        Categoria::create(
            [
                'nombre' => 'FAMILIARES',
            ]
        );
        Categoria::create(
            [
                'nombre' => 'VECINOS',
            ]
        );
        Categoria::create(
            [
                'nombre' => 'CIUDADANOS',
            ]
        );
        Categoria::create(
            [
                'nombre' => 'SIMPATIZANTES',
            ]
        );
        Categoria::create(
            [
                'nombre' => 'CIRCULOS',
            ]
        );
        Categoria::create(
            [
                'nombre' => 'MILITANTES',
            ]
        );
        Categoria::create(
            [
                'nombre' => 'CONSEJEROS',
            ]
        );
        Categoria::create(
            [
                'nombre' => 'REPRESENTANTES',
            ]
        );
        Categoria::create(
            [
                'nombre' => 'COMITES BASE',
            ]
        );
        Categoria::create(
            [
                'nombre' => 'DEL PARTIDO',
            ]
        );
        Categoria::create(
            [
                'nombre' => 'FUNCIONARIOS',
            ]
        );
        Categoria::create(
            [
                'nombre' => 'OTROS',
            ]
        );
    }
}
