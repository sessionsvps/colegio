<?php

namespace Database\Seeders;

use App\Models\Director;
use App\Models\Domicilio;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DirectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generar un código aleatorio de 5 dígitos
        do {
            $codigoDirector = str_pad(rand(0, 9999), 5, '0', STR_PAD_LEFT);
        } while (Director::where('codigo_director', $codigoDirector)->exists());

        // Crear el usuario
        $user = User::create([
            'email' => 'andreslujan@sideral.com',
            'password' => Hash::make('password'), // Usar una contraseña fija para todos los seeders
            'esActivo' => true,
        ]);

        // Asignar el rol al usuario (asegúrate de que el rol con ID 2 existe)
        $role = Role::findOrFail(5);
        $user->assignRole($role);

        // Crear el domicilio
        Domicilio::create([
            'user_id' => $user->id,
            'telefono_fijo' => '01 457191',
            'direccion' => 'Calle Aija 4900 Urb. Parque Naranjal',
            'departamento' => 15,
            'provincia' => 127,
            'distrito' => 1285,
        ]);

        // Crear el director
        Director::create([
            'codigo_director' => $codigoDirector,
            'user_id' => $user->id,
            'primer_nombre' => 'Jesus',
            'otros_nombres' => 'Andres',
            'apellido_paterno' => 'Lujan',
            'apellido_materno' => 'Carrion',
            'dni' => '47669000',
            'email' => 'andreslujan@sideral.com',
            'sexo' => 1,
            'telefono_celular' => '991001882',
            'id_estado' => 1,
            'fecha_nacimiento' => '1991-11-05',
            'nacionalidad' => 'Peruana',
            'departamento' => 15,
            'provincia' => 127,
            'distrito' => 1285,
            'fecha_ingreso' => '2020-05-05',
        ]);
    }
}
