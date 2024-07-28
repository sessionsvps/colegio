<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('instituciones')->insert([
            'codigo_modular' => '1554526057',
            'nombre' => 'Sideral Carrion',
            'direccion' => 'Av. Santa Clara 641',
            'ugel' => 'Trujillo',
            'nombre_director' => 'Jesús Andrés',
            'apellido_director' => 'Lujan Carrión',
        ]);
    }
}
