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

        Permission::create(['name' => 'estudiante.index'])->syncRoles([$role1,$role3]);
        Permission::create(['name' => 'estudiante.create'])->syncRoles([$role1]);
        Permission::create(['name' => 'estudiante.edit'])->syncRoles([$role1]);
        Permission::create(['name' => 'estudiante.destroy'])->syncRoles([$role1]);

        Permission::create(['name' => 'docente.index'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'docente.create'])->syncRoles([$role1]);
        Permission::create(['name' => 'docente.edit'])->syncRoles([$role1]);
        Permission::create(['name' => 'docente.destroy'])->syncRoles([$role1]);
    }
}
