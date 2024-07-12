<?php

namespace Database\Seeders;

use App\Models\Curso;
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
        $cursos = Curso::where('esActivo', 1)->get();
        $cursosYCompetencias = [];

        foreach ($cursos as $curso) {
            switch ($curso->descripcion) {
                case 'Educación para el trabajo':
                    $cursosYCompetencias[$curso->codigo_curso] = ['Gestiona proyectos de emprendimiento económico y social'];
                    break;
                case 'Educación física':
                    $cursosYCompetencias[$curso->codigo_curso] = [
                        'Se desenvuelve de manera autónoma a través de su motricidad',
                        'Asume una vida saludable',
                        'Interactúa a través de sus habilidades sociomotrices'
                    ];
                    break;
                case 'Personal social':
                    $cursosYCompetencias[$curso->codigo_curso] = [
                        'Construye su identidad',
                        'Convive y participa democráticamente',
                        'Construye interpretaciones históricas',
                        'Gestiona responsablemente el ambiente y el espacio',
                        'Gestiona responsablemente los recursos económicos'
                    ];
                    break;
                case 'Matemática':
                    $cursosYCompetencias[$curso->codigo_curso] = [
                        'Resuelve problemas de cantidad',
                        'Resuelve problemas de regularidad, equivalencia y cambio',
                        'Resuelve problemas de movimiento, forma y localización',
                        'Resuelve problemas de gestión de datos e incertidumbre'
                    ];
                    break;
                case 'Ciencias sociales':
                    $cursosYCompetencias[$curso->codigo_curso] = [
                        'Construye interpretaciones históricas',
                        'Gestiona responsablemente el ambiente y el espacio',
                        'Gestiona responsablemente los recursos económicos'
                    ];
                    break;
                case 'Castellano como segunda lengua':
                    $cursosYCompetencias[$curso->codigo_curso] = [
                        'Se comunica oralmente en castellano como segunda lengua',
                        'Lee diversos tipos de textos escritos en castellano como segunda lengua',
                        'Escribe diversos tipos de textos castellano como segunda lengua'
                    ];
                    break;
                case 'Educación religiosa':
                    $cursosYCompetencias[$curso->codigo_curso] = [
                        'Construye su identidad como persona humana, amada por Dios, digna, libre y trascendente',
                        'Asume la experiencia el encuentro personal y comunitario con Dios'
                    ];
                    break;
                case 'Ciencia y tecnología':
                    $cursosYCompetencias[$curso->codigo_curso] = [
                        'Indaga mediante métodos científicos',
                        'Explica el mundo físico basándose en conocimientos sobre los seres vivos; materia y energía; biodiversidad, Tierra y universo',
                        'Diseña y construye soluciones tecnológicas para resolver problemas'
                    ];
                    break;
                case 'Comunicación':
                    $cursosYCompetencias[$curso->codigo_curso] = [
                        'Se comunica oralmente en lengua materna',
                        'Lee diversos tipos de textos escritos',
                        'Escribe diversos tipos de textos'
                    ];
                    break;
                case 'Inglés':
                    $cursosYCompetencias[$curso->codigo_curso] = [
                        'Se comunica oralmente en inglés como lengua extranjera',
                        'Lee diversos tipos de textos en inglés como lengua extranjera',
                        'Escribe diversos tipos de textos en inglés como lengua extranjera'
                    ];
                    break;
                case 'Desarrollo personal, ciudadanía y cívica':
                    $cursosYCompetencias[$curso->codigo_curso] = [
                        'Construye su identidad',
                        'Convive y participa democráticamente'
                    ];
                    break;
                case 'Arte y cultura':
                    $cursosYCompetencias[$curso->codigo_curso] = [
                        'Aprecia de manera crítica manifestaciones artístico-culturales',
                        'Crea proyectos desde los lenguajes artísticos'
                    ];
                    break;
            }
        }

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
