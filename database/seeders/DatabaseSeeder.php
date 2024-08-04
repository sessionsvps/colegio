<?php

namespace Database\Seeders;

use App\Models\Institucion;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            EstadoSeeder::class,
            CursoSeeder::class,
            NivelSeeder::class,
            GradoSeeder::class,
            SeccionSeeder::class,
            Curso_por_nivelSeeder::class,
            DocenteSeeder::class,
            EstudianteSeeder::class,
            CompetenciaSeeder::class,
            BimestreSeeder::class,
            InstitucionSeeder::class,
            DirectorSeeder::class,
            SecretariaSeeder::class,
        ]);

        User::factory()->create()->assignRole('Admin');
    }
}
