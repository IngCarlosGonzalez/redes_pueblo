<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Contacto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $this->call(AdminSeeder::class);

        $this->call(CategoriaSeeder::class);
        
        $this->call(MunicipioSeeder::class);

        Contacto::factory(1000)->create();
    }
}
