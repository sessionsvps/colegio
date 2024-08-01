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
        $role3 = Role::create(['name' => 'Estudiante_Registrado']);
        $role4 = Role::create(['name' => 'Estudiante_Matriculado']);

        Permission::create(['name' => 'users.control'])->syncRoles([$role1]);
        Permission::create(['name' => 'estudiantes.control'])->syncRoles([$role1]);
        Permission::create(['name' => 'docentes.control'])->syncRoles([$role1]);
        Permission::create(['name' => 'exoneraciones.create'])->syncRoles([$role1]);
        Permission::create(['name' => 'asistencias.create'])->syncRoles([$role1]);
        
        Permission::create(['name' => 'exoneraciones.index'])->syncRoles([$role1,$role4]);
        Permission::create(['name' => 'asistencias.index'])->syncRoles([$role1,$role4]);

        Permission::create(['name' => 'notas.admin'])->syncRoles([$role1]);
        Permission::create(['name' => 'notas.create'])->syncRoles([$role2]);
        Permission::create(['name' => 'notas.index'])->syncRoles([$role1,$role4]);

        Permission::create(['name' => 'cursos.info'])->syncRoles([$role1,$role4]);
        Permission::create(['name' => 'cursos.info_docente'])->syncRoles([$role2]);
        Permission::create(['name' => 'cursos.index'])->syncRoles([$role1,$role2,$role4]);



    }
}
