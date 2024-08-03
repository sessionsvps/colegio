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
        $role5 = Role::create(['name' => 'Director']);
        $role6 = Role::create(['name' => 'Secretaria']);

        // Permission::create(['name' => 'users.control'])->syncRoles([$role1]);
        // Permission::create(['name' => 'estudiantes.control'])->syncRoles([$role1]);
        // Permission::create(['name' => 'docentes.control'])->syncRoles([$role1]);
        // Permission::create(['name' => 'exoneraciones.create'])->syncRoles([$role1]);
        // Permission::create(['name' => 'asistencias.create'])->syncRoles([$role1]);
        
        // Permission::create(['name' => 'exoneraciones.index'])->syncRoles([$role1,$role4]);
        // Permission::create(['name' => 'asistencias.index'])->syncRoles([$role1,$role4]);

        // Permission::create(['name' => 'notas.admin'])->syncRoles([$role1]);
        // Permission::create(['name' => 'notas.create'])->syncRoles([$role2]);
        // Permission::create(['name' => 'notas.index'])->syncRoles([$role1,$role4]);

        // Permission::create(['name' => 'cursos.info'])->syncRoles([$role1,$role4]);
        // Permission::create(['name' => 'cursos.info_docente'])->syncRoles([$role2]);
        // Permission::create(['name' => 'cursos.index'])->syncRoles([$role1,$role2,$role4]);

        // Usuarios
        Permission::create(['name' => 'Ver Usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar Usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'Eliminar Usuarios'])->syncRoles([$role1]);

        // Docentes
        Permission::create(['name' => 'Ver Docentes'])->syncRoles([$role1,$role5,$role6]);
        Permission::create(['name' => 'Registrar Docentes'])->syncRoles([$role6]);
        Permission::create(['name' => 'Editar Docentes'])->syncRoles([$role1,$role6,$role2]);
        Permission::create(['name' => 'Eliminar Docentes'])->syncRoles([$role1,$role6]);

        // Estudiantes
        Permission::create(['name' => 'Ver Estudiantes'])->syncRoles([$role1, $role5, $role6]);
        Permission::create(['name' => 'Registrar Estudiantes'])->syncRoles([$role6]);
        Permission::create(['name' => 'Editar Estudiantes'])->syncRoles([$role1, $role6, $role4]);
        Permission::create(['name' => 'Eliminar Estudiantes'])->syncRoles([$role1, $role6]);

        // Matriculas
        Permission::create(['name' => 'Registrar Matriculas'])->syncRoles([$role6]);
        Permission::create(['name' => 'Editar Matriculas'])->syncRoles([$role1, $role6]);
        Permission::create(['name' => 'Eliminar Matriculas'])->syncRoles([$role1, $role6]);

        // Cátedras
        Permission::create(['name' => 'Ver Catedras'])->syncRoles([$role1, $role5, $role6]);
        Permission::create(['name' => 'Registrar Catedras'])->syncRoles([$role6]);
        Permission::create(['name' => 'Editar Catedras'])->syncRoles([$role6]);
        Permission::create(['name' => 'Eliminar Catedras'])->syncRoles([$role6]);

        // Director
        Permission::create(['name' => 'Ver Director'])->syncRoles([$role1]);
        Permission::create(['name' => 'Registrar Director'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar Director'])->syncRoles([$role1,$role5]);
        Permission::create(['name' => 'Eliminar Director'])->syncRoles([$role1]);

        // Secretarias
        Permission::create(['name' => 'Ver Secretarias'])->syncRoles([$role1, $role5]);
        Permission::create(['name' => 'Registrar Secretarias'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar Secretarias'])->syncRoles([$role1,$role6]);
        Permission::create(['name' => 'Eliminar Secretarias'])->syncRoles([$role1]);

        // Asistencias
        Permission::create(['name' => 'Ver Asistencias'])->syncRoles([$role1, $role4, $role5, $role6]);
        Permission::create(['name' => 'Editar Asistencias'])->syncRoles([$role6]);

        // Exoneraciones
        Permission::create(['name' => 'Ver Exoneraciones'])->syncRoles([$role1, $role4, $role5, $role6]);
        Permission::create(['name' => 'Editar Exoneraciones'])->syncRoles([$role6]);

        // Notas
        Permission::create(['name' => 'Ver Notas'])->syncRoles([$role1, $role4, $role5, $role6]);
        Permission::create(['name' => 'Editar Notas'])->syncRoles([$role2]);

        // Cursos
        Permission::create(['name' => 'Ver Cursos'])->syncRoles([$role1, $role2, $role4, $role5, $role6]);

        // Aulas
        Permission::create(['name' => 'Ver Aulas'])->syncRoles([$role1, $role4, $role5, $role6]);

        // Roles y Permisos
        Permission::create(['name' => 'Ver Roles y Permisos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Registrar Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'Eliminar Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'Registrar Permisos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar Permisos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Eliminar Permisos'])->syncRoles([$role1]);

        // Reportes
        Permission::create(['name' => 'Generar reporte de Estudiantes'])->syncRoles([$role1, $role2, $role4, $role5, $role6]);
        Permission::create(['name' => 'Generar reporte de Docentes'])->syncRoles([$role1, $role5, $role6]);
        Permission::create(['name' => 'Generar reporte de Cursos'])->syncRoles([$role1, $role2, $role4, $role5, $role6]);
        Permission::create(['name' => 'Generar reporte de Notas'])->syncRoles([$role1, $role2, $role4, $role5, $role6]);

    }
}
