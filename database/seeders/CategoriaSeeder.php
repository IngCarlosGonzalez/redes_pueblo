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
                'clasificacion' => 'GENERAL',
            ]
        );
        Categoria::create(
            [
                'clasificacion' => 'AMISTADES',
            ]
        );
        Categoria::create(
            [
                'clasificacion' => 'CIUDADANOS',
            ]
        );
        Categoria::create(
            [
                'clasificacion' => 'SIMPATIZANTES',
            ]
        );
        Categoria::create(
            [
                'clasificacion' => 'CIRCULOS',
            ]
        );
        Categoria::create(
            [
                'clasificacion' => 'MILITANTES',
            ]
        );
        Categoria::create(
            [
                'clasificacion' => 'CONSEJEROS',
            ]
        );
        Categoria::create(
            [
                'clasificacion' => 'REPRESENTANTES',
            ]
        );
        Categoria::create(
            [
                'clasificacion' => 'COMITES',
            ]
        );
        Categoria::create(
            [
                'clasificacion' => 'FUNCIONARIOS',
            ]
        );
        Categoria::create(
            [
                'clasificacion' => 'GESTORES',
            ]
        );
    }
}
