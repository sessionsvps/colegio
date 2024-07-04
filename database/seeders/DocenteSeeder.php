<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Docente;
use App\Models\User;
use App\Models\Domicilio;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class DocenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 15; $i++) {
            // Generar un código docente aleatorio de 4 dígitos
            do {
                $codigoDocente = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            } while (Docente::where('codigo_docente', $codigoDocente)->exists());

            // Crear el usuario
            $user = User::create([
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), // Usar una contraseña fija para todos los seeders
                'esActivo' => true,
            ]);

            // Asignar el rol al usuario (asegúrate de que el rol con ID 2 existe)
            $role = Role::findOrFail(2);
            $user->assignRole($role);

            // Crear el domicilio
            Domicilio::create([
                'user_id' => $user->id,
                'telefono_fijo' => $faker->optional()->regexify('[0-9]{7,9}'),
                'direccion' => $faker->address,
                'departamento' => $faker->state,
                'provincia' => $faker->city,
                'distrito' => $faker->citySuffix,
            ]);

            // Crear el docente
            Docente::create([
                'codigo_docente' => $codigoDocente,
                'user_id' => $user->id,
                'primer_nombre' => $faker->firstName,
                'otros_nombres' => $faker->optional()->firstName,
                'apellido_paterno' => $faker->lastName,
                'apellido_materno' => $faker->lastName,
                'dni' => $faker->unique()->numerify('########'),
                'email' => $user->email,
                'sexo' => $faker->randomElement([0, 1]),
                'telefono_celular' => $faker->optional()->regexify('[0-9]{9}'),
                'id_estado' => 1,
                'fecha_nacimiento' => $faker->date('Y-m-d', '2000-01-01'),
                'nacionalidad' => 'Peruano',
                'departamento' => $faker->state,
                'provincia' => $faker->city,
                'distrito' => $faker->citySuffix,
                'esTutor' => false,
                'fecha_ingreso' => $faker->date('Y-m-d', 'now'),
            ]);
        }
    }
}
