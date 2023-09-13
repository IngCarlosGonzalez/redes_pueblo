<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // aqui se definin los roles
        $role1 = Role::create([
            'name' => 'ADMINISTRADOR'
        ]);
        $role2 = Role::create([
            'name' => 'COORDINADOR'
        ]);
        $role3 = Role::create([
            'name' => 'OPERADOR'
        ]);
        $role4 = Role::create([
            'name' => 'PROMOTOR'
        ]);

        // aqui se definen los primeros permisos y se asignan a roles
        Permission::create([
            'name' => 'tareas administrador'
        ])->assignRole($role1);
        Permission::create([
            'name' => 'tareas coordinador'
        ])->assignRole($role2);
        Permission::create([
            'name' => 'tareas operador'
        ])->assignRole($role3);
        Permission::create([
            'name' => 'tareas promotor'
        ])->assignRole($role4);

        // cuando son varios roles para mismo permiso se usa:
        //Permission::create(['name' => 'permiso'])->syncRoles([$role1, $role2]);
    }
}
