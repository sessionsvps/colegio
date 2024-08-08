<?php

namespace Database\Seeders;

use App\Models\Domicilio;
use App\Models\Secretaria;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SecretariaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 5; $i++) {
            // Generar un código aleatorio de 5 dígitos
            do {
                $codigoSecretaria = str_pad(rand(0, 9999), 5, '0', STR_PAD_LEFT);
            } while (Secretaria::where('codigo_secretaria', $codigoSecretaria)->exists());

            // Crear el usuario
            $user = User::create([
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), // Usar una contraseña fija para todos los seeders
                'esActivo' => true,
            ]);

            // Asignar el rol al usuario (asegúrate de que el rol con ID 2 existe)
            $role = Role::findOrFail(6);
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

            // Crear la secretaria
            Secretaria::create([
                'codigo_secretaria' => $codigoSecretaria,
                'user_id' => $user->id,
                'primer_nombre' => $faker->firstName,
                'otros_nombres' => $faker->optional()->firstName,
                'apellido_paterno' => $faker->lastName,
                'apellido_materno' => $faker->lastName,
                'dni' => $faker->unique()->numerify('########'),
                'email' => $user->email,
                'sexo' => 0,
                'telefono_celular' => $faker->optional()->regexify('[0-9]{9}'),
                'id_estado' => 1,
                'fecha_nacimiento' => $faker->date('Y-m-d', '2000-01-01'),
                'nacionalidad' => 'Peruano',
                'departamento' => $faker->state,
                'provincia' => $faker->city,
                'distrito' => $faker->citySuffix,
                'fecha_ingreso' => $faker->dateTimeBetween('2020-01-01', '2024-12-31')->format('Y-m-d'),
            ]);
        }
    }
}
