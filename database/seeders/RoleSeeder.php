<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'Admin']);
        $role2 = Role::create(['name' => 'Docente']);
        $role3 = Role::create(['name' => 'Estudiante']);

        Permission::create(['name' => 'users.control'])->syncRoles([$role1]);

        Permission::create(['name' => 'estudiantes.index'])->syncRoles([$role1,$role3]);
        Permission::create(['name' => 'estudiantes.control'])->syncRoles([$role1]);

        Permission::create(['name' => 'docentes.index'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'docentes.control'])->syncRoles([$role1]);
    }
}
