<?php

namespace Database\Seeders;

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
        // User::factory(10)->create();

        $this->call(RoleSeeder::class);

        User::factory()->create()->assignRole('Admin');

        $this->call([
            EstadoSeeder::class,
            CursoSeeder::class,
            NivelSeeder::class,
            GradoSeeder::class,
            SeccionSeeder::class,
            Curso_por_nivelSeeder::class,
            DocenteSeeder::class,
            EstudianteSeeder::class,
            CompetenciaSeeder::class,
        ]);
    }
}
