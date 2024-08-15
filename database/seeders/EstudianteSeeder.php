<?php

namespace Database\Seeders;

use App\Models\Apoderado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Estudiante;
use App\Models\Domicilio;
use App\Models\Estudiante_Seccion;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class EstudianteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 15; $i++) {
            // Generar un código estudiante aleatorio de 10 dígitos
            do {
                $codigoEstudiante = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            } while (Estudiante::where('codigo_estudiante', $codigoEstudiante)->exists());

            // Crear el usuario para el apoderado
            $userApoderado = User::create([
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), // Usar una contraseña genérica
                'esActivo' => true,
            ]);

            // Crear el apoderado
            $apoderado = Apoderado::create([
                'user_id' => $userApoderado->id,
                'primer_nombre' => $faker->firstName,
                'otros_nombres' => $faker->optional()->firstName,
                'apellido_paterno' => $faker->lastName,
                'apellido_materno' => $faker->lastName,
                'dni' => $faker->unique()->numerify('########'),
                'email' => $userApoderado->email,
                'telefono_celular' => $faker->optional()->regexify('[0-9]{9}'),
                'fecha_nacimiento' => $faker->date,
                'sexo' => $faker->boolean,
            ]);

            // Crear el usuario para el estudiante
            $userEstudiante = User::create([
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), // Usar una contraseña genérica
                'esActivo' => true,
            ]);

            // Crear el domicilio
            Domicilio::create([
                'user_id' => $userEstudiante->id,
                'telefono_fijo' => $faker->optional()->regexify('[0-9]{7,9}'),
                'direccion' => $faker->address,
                'departamento' => 15,
                'provincia' => 127,
                'distrito' => 1272,
            ]);

            // Crear el estudiante
            Estudiante::create([
                'codigo_estudiante' => $codigoEstudiante,
                'user_id' => $userEstudiante->id,
                'id_apoderado' => $apoderado->id,
                'primer_nombre' => $faker->firstName,
                'otros_nombres' => $faker->optional()->firstName,
                'apellido_paterno' => $faker->lastName,
                'apellido_materno' => $faker->lastName,
                'dni' => $faker->unique()->numerify('########'),
                'email' => $userEstudiante->email,
                'telefono_celular' => $faker->optional()->regexify('[0-9]{9}'),
                'fecha_nacimiento' => $faker->date,
                'sexo' => $faker->boolean,
                'nro_matricula' => null,
                'año_ingreso' => $faker->numberBetween(2020, 2024),
                'lengua_materna' => $faker->word,
                'colegio_procedencia' => $faker->optional()->company,
                'nacionalidad' => 'Peruana',
                'departamento' => 15,
                'provincia' => 127,
                'distrito' => 1272,
            ]);
        }
    }
}
