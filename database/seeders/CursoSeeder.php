<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cursos = [
            ['descripcion' => 'Comunicación', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['descripcion' => 'Castellano como segunda lengua', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['descripcion' => 'Inglés', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['descripcion' => 'Arte y cultura', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['descripcion' => 'Personal social', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['descripcion' => 'Desarrollo personal, ciudadanía y cívica', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['descripcion' => 'Ciencias sociales', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['descripcion' => 'Educación religiosa', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['descripcion' => 'Educación física', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['descripcion' => 'Ciencia y tecnología', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['descripcion' => 'Educación para el trabajo', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['descripcion' => 'Matemática', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['descripcion' => 'Tutoría y orientación educativa', 'año_actualizacion' => 2024, 'esActivo' => true],
        ];

        foreach ($cursos as &$curso) {
            $curso['codigo_curso'] = $this->generateRandomCode();
        }

        DB::table('cursos')->insert($cursos);
    }

    private function generateRandomCode()
    {
        do {
            $codigo = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (DB::table('cursos')->where('codigo_curso', $codigo)->exists());

        return $codigo;
    }
}
