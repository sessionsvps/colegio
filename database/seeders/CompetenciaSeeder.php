<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompetenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cursosYCompetencias = [
            '1673' => ['Gestiona proyectos de emprendimiento económico y social'],
            '3512' => ['Se desenvuelve de manera autónoma a través de su motricidad', 'Asume una vida saludable', 'Interactúa a través de sus habilidades sociomotrices'],
            '4491' => ['Construye su identidad', 'Convive y participa democráticamente', 'Construye interpretaciones históricas', 'Gestiona responsablemente el ambiente y el espacio', 'Gestiona responsablemente los recursos económicos'],
            '4550' => ['Resuelve problemas de cantidad', 'Resuelve problemas de regularidad, equivalencia y cambio', 'Resuelve problemas de movimiento, forma y localización', 'Resuelve problemas de gestión de datos e incertidumbre'],
            '4896' => ['Construye interpretaciones históricas', 'Gestiona responsablemente el ambiente y el espacio', 'Gestiona responsablemente los recursos económicos'],
            '5073' => ['Se comunica oralmente en castellano como segunda lengua', 'Lee diversos tipos de textos escritos en castellano como segunda lengua', 'Escribe diversos tipos de textos castellano como segunda lengua'],
            '6209' => ['Construye su identidad como persona humana, amada por Dios, digna, libre y trascendente', 'Asume la experiencia el encuentro personal y comunitario con Dios'],
            '7421' => ['Indaga mediante métodos científicos', 'Explica el mundo físico basándose en conocimientos sobre los seres vivos; materia y energía; biodiversidad, Tierra y universo', 'Diseña y construye soluciones tecnológicas para resolver problemas'],
            '8481' => ['Se comunica oralmente en lengua materna', 'Lee diversos tipos de textos escritos', 'Escribe diversos tipos de textos'],
            '8507' => ['Se comunica oralmente en inglés como lengua extranjera', 'Lee diversos tipos de textos en inglés como lengua extranjera', 'Escribe diversos tipos de textos inglés como lengua extranjera'],
            '9958' => ['Construye su identidad', 'Convive y participa democráticamente'],
            '9963' => ['Aprecia de manera crítica manifestaciones artístico-culturales', 'Crea proyectos desde los lenguajes artísticos'],
            // Agrega las competencias reales aquí
        ];

        foreach ($cursosYCompetencias as $codigoCurso => $competencias) {
            $orden = 1; // Inicializar el orden en 1 para cada curso
            foreach ($competencias as $competencia) {
                DB::table('competencias')->insert([
                    'codigo_curso' => $codigoCurso,
                    'orden' => $orden,
                    'descripcion' => $competencia // Asegúrate de tener una columna 'competencia' en tu tabla
                ]);
                $orden++;
            }
        }
    }
}
