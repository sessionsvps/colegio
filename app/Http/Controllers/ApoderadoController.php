<?php

namespace App\Http\Controllers;

use App\Models\Apoderado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApoderadoController extends Controller
{

    public function index(Request $request)
    {
        $auth = Auth::user()->id;
        $user = User::findOrFail($auth);

        switch (true) {
            case $user->hasRole('Admin'):
            case $user->hasRole('Secretaria'):
            case $user->hasRole('Director'):

                $query = Apoderado::whereHas('user', function ($query) {
                    $query->where('esActivo', 1);
                });

                // if ($request->filled('filtrar_por')) {
                //     $filtrarPor = $request->input('filtrar_por');
                //     if ($filtrarPor == 'matriculado') {
                //         $query->whereNotNull('nro_matricula')->get();
                //     } else if ($filtrarPor == 'no_matriculado') {
                //         $query->where('nro_matricula', null)->get();
                //     }
                // }

                // if ($request->filled('año_ingreso')) {
                //     $query->where('año_ingreso', $request->input('año_ingreso'))->get();
                // }

                // if ($request->filled('buscar_por')) {
                //     $buscarPor = $request->input('buscar_por');
                //     $buscarValor = $request->input($buscarPor);

                //     if ($buscarPor === 'codigo') {
                //         $query->where('codigo_estudiante', 'like', '%' . $buscarValor . '%');
                //     } elseif ($buscarPor === 'nombre') {
                //         $query->where(function ($query) use ($buscarValor) {
                //             $query->where(DB::raw("CONCAT(primer_nombre, ' ', otros_nombres, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscarValor . '%')
                //                 ->orWhere(DB::raw("CONCAT(primer_nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscarValor . '%');
                //         });
                //     } elseif ($buscarPor === 'dni') {
                //         $query->where('dni', 'like', '%' . $buscarValor . '%');
                //     } elseif ($buscarPor === 'correo') {
                //         $query->whereHas('user', function ($query) use ($buscarValor) {
                //             $query->where('email', 'like', '%' . $buscarValor . '%');
                //         });
                //     }
                // }
                $apoderados = $query->paginate(10);
                return view('apoderados.index', compact('apoderados'));
            default:
                break;
        }
    }

    public function edit(string $id)
    {
        $apoderado = Apoderado::findOrFail($id);
        return view('apoderados.edit', compact('apoderado'));
    }

    public function update(Request $request, string $id)
    {
        $apoderado = Apoderado::findOrFail($id);

        $request->validate([
            'primer_nombre' => 'required|string|max:30',
            'otros_nombres' => 'nullable|string|max:30',
            'apellido_paterno' => 'required|string|max:30',
            'apellido_materno' => 'required|string|max:30',
            'dni' => 'required|string|size:8|unique:estudiantes,dni,' . $apoderado->id . ',codigo_estudiante',
            'email' => 'required|string|email|max:50|unique:estudiantes,email,' . $apoderado->id . ',codigo_estudiante',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|boolean',
            'telefono_celular' => 'nullable|string|size:9',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024'
        ]);

        // Actualizar datos del estudiante
        $apoderado->update([
            'primer_nombre' => $request->primer_nombre,
            'otros_nombres' => $request->otros_nombres,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'dni' => $request->dni,
            'email' => $request->email,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'sexo' => $request->sexo,
            'telefono_celular' => $request->telefono_celular,
        ]);

        if ($request->hasFile('photo')) {
            $apoderado->user->updateProfilePhoto($request->file('photo'));
        }

        return redirect()->route('apoderados.index')->with('success', 'Apoderado actualizado exitosamente.');
    }

    public function buscarPorDni(Request $request)
    {
        $dni = $request->query('dni');
        $apoderado = Apoderado::where('dni', $dni)->first();

        if ($apoderado) {
            $photoUrl = $apoderado->user->profile_photo_url;
            return response()->json(['success' => true, 'apoderado' => $apoderado, 'photo_url' => $photoUrl]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
