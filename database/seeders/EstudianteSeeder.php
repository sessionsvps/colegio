<?php

namespace Database\Seeders;

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

            // Crear el usuario
            $user = User::create([
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), // Usar una contraseña genérica
                'esActivo' => true,
            ]);

            // Asignar el rol al usuario
            $role = Role::findOrFail(3); // Asegúrate de que el ID 3 corresponda al rol correcto
            $user->assignRole($role);

            // Crear el domicilio
            Domicilio::create([
                'user_id' => $user->id,
                'telefono_fijo' => $faker->optional()->regexify('[0-9]{7,9}'),
                'direccion' => $faker->address,
                'departamento' => $faker->state,
                'provincia' => $faker->city,
                'distrito' => $faker->city,
            ]);

            // Crear el estudiante
            Estudiante::create([
                'codigo_estudiante' => $codigoEstudiante,
                'user_id' => $user->id,
                'primer_nombre' => $faker->firstName,
                'otros_nombres' => $faker->optional()->firstName,
                'apellido_paterno' => $faker->lastName,
                'apellido_materno' => $faker->lastName,
                'dni' => $faker->unique()->numerify('########'),
                'email' => $user->email,
                'telefono_celular' => $faker->optional()->regexify('[0-9]{9}'),
                'fecha_nacimiento' => $faker->date,
                'sexo' => $faker->boolean,
                'nro_matricula' => null,
                'año_ingreso' => $faker->numberBetween(2020, 2024),
                'lengua_materna' => $faker->word,
                'colegio_procedencia' => $faker->optional()->company,
                'nacionalidad' => $faker->country,
                'departamento' => $faker->state,
                'provincia' => $faker->city,
                'distrito' => $faker->city,
            ]);

            // Llenar la tabla intermedia
            // Estudiante_Seccion::create([
            //     'codigo_estudiante' => $codigoEstudiante,
            //     'user_id' => $user->id,
            //     'año_escolar' => $faker->year,
            //     'id_nivel' => $faker->numberBetween(1, 2),
            //     'id_grado' => $faker->numberBetween(1, 6),
            //     'id_seccion' => $faker->numberBetween(1, 4),
            // ]);
        }
    }
}
