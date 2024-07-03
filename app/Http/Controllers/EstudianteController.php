<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controller as BaseController;

class EstudianteController extends BaseController
{

    public function __construct()
    {
        $this->middleware('can:estudiantes.index')->only('index');
        $this->middleware('can:estudiantes.control')->only('create','store','edit','update','destroy');
    }

    public function index()
    {
        $estudiantes = Estudiante::whereHas('user', function ($query) {
            $query->where('esActivo', 1);
        })->get();
        return view('estudiantes.index', compact('estudiantes'));
    }

    public function create()
    {
        return view('estudiantes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'primer_nombre' => 'required|string|max:30',
            'otros_nombres' => 'nullable|string|max:30',
            'apellido_paterno' => 'required|string|max:30',
            'apellido_materno' => 'required|string|max:30',
            'dni' => 'required|string|min:8|max:8|unique:estudiantes,dni',
            'email' => 'required|string|email|max:50|unique:estudiantes,email',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|boolean',
            'año_ingreso' => 'required|integer',
            'lengua_materna' => 'required|string|max:30',
            'colegio_procedencia' => 'nullable|string|max:50',
            'password' => 'required|string|min:8|max:30', // Validación de la contraseña
        ]);

        // Generar un código estudiante aleatorio de 10 dígitos
        do {
            $codigoEstudiante = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (Estudiante::where('codigo_estudiante', $codigoEstudiante)->exists());

        do {
            $nroMatricula = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
        } while (Estudiante::where('nro_matricula', $nroMatricula)->exists());

        // Crear el usuario
        $user = User::create([
            'name' => $request->input('primer_nombre'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')), // Hashear la contraseña
            'esActivo' => True,
        ]);

        // Asignar el rol al usuario
        $role = Role::findOrFail(3);
        $user->assignRole($role);

        // Crear el estudiante
        $estudiante = Estudiante::create([
            'codigo_estudiante' => $codigoEstudiante,
            'user_id' => $user->id,
            'primer_nombre' => $request->input('primer_nombre'),
            'otros_nombres' => $request->input('otros_nombres'),
            'apellido_paterno' => $request->input('apellido_paterno'),
            'apellido_materno' => $request->input('apellido_materno'),
            'dni' => $request->input('dni'),
            'email' => $request->input('email'),
            'fecha_nacimiento' => $request->input('fecha_nacimiento'),
            'sexo' => $request->input('sexo'),
            'nro_matricula' => $nroMatricula,
            'año_ingreso' => $request->input('año_ingreso'),
            'lengua_materna' => $request->input('lengua_materna'),
            'colegio_procedencia' => $request->input('colegio_procedencia'),
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante registrado exitosamente.');

    }

    public function edit(string $codigo_estudiante)
    {
        $estudiante = Estudiante::findOrFail($codigo_estudiante);
        return view('estudiantes.edit', compact('estudiante'));
    }

    public function update(Request $request, string $codigo_estudiante)
    {
        $estudiante = Estudiante::findOrFail($codigo_estudiante);

        $request->validate([
            'primer_nombre' => 'nullable|string|max:30',
            'otros_nombres' => 'nullable|string|max:30',
            'apellido_paterno' => 'nullable|string|max:30',
            'apellido_materno' => 'nullable|string|max:30',
            'dni' => 'nullable|string|size:8|unique:estudiantes,dni,' . $estudiante->codigo_estudiante . ',codigo_estudiante',
            'email' => 'nullable|string|email|max:50|unique:estudiantes,email,' . $estudiante->codigo_estudiante . ',codigo_estudiante',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|boolean',
            'año_ingreso' => 'nullable|integer',
            'lengua_materna' => 'nullable|string|max:30',
            'colegio_procedencia' => 'nullable|string|max:50',
        ]);

        // Actualizar los datos del Estudiante
        $estudiante->update($request->all());

        // Actualizar los datos del Usuario asociado solo si han cambiado
        $userData = [];
        if ($request->input('primer_nombre') !== $estudiante->user->name) {
            $userData['name'] = $request->input('primer_nombre');
        }
        if ($request->input('email') !== $estudiante->user->email) {
            $userData['email'] = $request->input('email');
        }

        if (!empty($userData)) {
            $estudiante->user->update($userData);
        }

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado exitosamente.');
    }

    public function destroy(string $codigo_estudiante)
    {
        $estudiante = Estudiante::findOrFail($codigo_estudiante);
        $user = User::findOrFail($estudiante->user_id);
        $user->esActivo = 0;
        $user->save();
        return redirect()->route('estudiantes.index')->with('success', 'Estudiante eliminado exitosamente.');
    }
}
