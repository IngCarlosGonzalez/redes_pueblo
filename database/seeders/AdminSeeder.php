<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(
                  [
                      'name' => 'Carlos Gonzalez',
                      'email' => 'cegcdeveloper@gmail.com',
                      'password' => Hash::make('rufirufi'),
                      'email_verified_at' => now(),
                      'remember_token' => Str::random(10),
                      'profile_photo_path' => 'logos/MINDMAN.JPG',
                      'is_active' => 1,
                      'is_admin' => 1,
                      'level_id' => 1,
                  ]
              )->assignRole('ADMINISTRADOR');
    }
}
