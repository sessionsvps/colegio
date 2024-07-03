<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Curso_por_nivelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asumiendo que los niveles Primaria y Secundaria tienen id_nivel 1 y 2 respectivamente.
        $idNivelPrimaria = 1;
        $idNivelSecundaria = 2;

        // Cursos para Primaria y Secundaria
        $cursosPrimaria = [
            'Personal social',
        ];

        $cursosSecundaria = [
            'Desarrollo personal, ciudadanía y cívica',
            'Ciencias sociales',
            'Educación para el trabajo',
        ];

        $cursosAmbosNiveles = [
            'Comunicación',
            'Castellano como segunda lengua',
            'Inglés',
            'Arte y cultura',
            'Educación religiosa',
            'Educación física',
            'Ciencia y tecnología',
            'Matemática',
            'Tutoría y orientación educativa',
        ];

        $cursoPorNiveles = [];

        // Obtener los cursos desde la base de datos
        $cursos = DB::table('cursos')->get();

        foreach ($cursos as $curso) {
            // Asignar cursos a Primaria y/o Secundaria según corresponda
            if (in_array($curso->descripcion, $cursosPrimaria)) {
                $cursoPorNiveles[] = [
                    'codigo_curso' => $curso->codigo_curso,
                    'id_nivel' => $idNivelPrimaria,
                ];
            }

            if (in_array($curso->descripcion, $cursosSecundaria)) {
                $cursoPorNiveles[] = [
                    'codigo_curso' => $curso->codigo_curso,
                    'id_nivel' => $idNivelSecundaria,
                ];
            }

            if (in_array($curso->descripcion, $cursosAmbosNiveles)) {
                $cursoPorNiveles[] = [
                    'codigo_curso' => $curso->codigo_curso,
                    'id_nivel' => $idNivelPrimaria,
                ];
                $cursoPorNiveles[] = [
                    'codigo_curso' => $curso->codigo_curso,
                    'id_nivel' => $idNivelSecundaria,
                ];
            }
        }

        DB::table('curso_por_niveles')->insert($cursoPorNiveles);
    }

}
