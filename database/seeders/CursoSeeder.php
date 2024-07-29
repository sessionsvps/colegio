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
            ['codigo_curso' => '8520' ,'descripcion' => 'Comunicación', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['codigo_curso' => '7670' ,'descripcion' => 'Castellano como segunda lengua', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['codigo_curso' => '3995' ,'descripcion' => 'Inglés', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['codigo_curso' => '4159' ,'descripcion' => 'Arte y cultura', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['codigo_curso' => '9645' ,'descripcion' => 'Personal social', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['codigo_curso' => '7341' ,'descripcion' => 'Desarrollo personal, ciudadanía y cívica', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['codigo_curso' => '0342' ,'descripcion' => 'Ciencias sociales', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['codigo_curso' => '3965' ,'descripcion' => 'Educación religiosa', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['codigo_curso' => '5350' ,'descripcion' => 'Educación física', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['codigo_curso' => '8927' ,'descripcion' => 'Ciencia y tecnología', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['codigo_curso' => '5021' ,'descripcion' => 'Educación para el trabajo', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['codigo_curso' => '8947' ,'descripcion' => 'Matemática', 'año_actualizacion' => 2024, 'esActivo' => true],
            ['codigo_curso' => '8889' ,'descripcion' => 'Tutoría y orientación educativa', 'año_actualizacion' => 2024, 'esActivo' => true],
        ];

        DB::table('cursos')->insert($cursos);
    }
}
