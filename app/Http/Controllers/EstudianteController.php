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
            'name' => 'required|string|max:100',
            'dni' => 'required|string|min:8|max:8|unique:estudiantes,dni',
            'email' => 'required|string|email|max:100|unique:estudiantes,email',
            'grade' => 'required|string',
            'level' => 'required|string',
            'section' => 'required|string',
            'password' => 'required|string|min:8|max:30', // Validación de la contraseña
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')), // Hashear la contraseña
            'esActivo' => True,
        ]);

        // Asignar el rol al usuario
        $role = Role::findOrFail(3);
        $user->assignRole($role);

        // Crear el estudiante
        $estudiante = Estudiante::create([
            'name' => $request->input('name'),
            'dni' => $request->input('dni'),
            'email' => $request->input('email'),
            'grade' => $request->input('grade'),
            'level' => $request->input('level'),
            'section' => $request->input('section'),
            'user_id' => $user->id, // Relaciona el docente con el usuario
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante registrado exitosamente.');

    }

    public function edit(string $id)
    {
        $estudiante = Estudiante::findOrFail($id);
        return view('estudiantes.edit', compact('estudiante'));
    }

    public function update(Request $request, string $id)
    {
        $estudiante = Estudiante::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:100',
            'dni' => 'nullable|string|min:8|max:8|unique:estudiantes,dni,' . $estudiante->id,
            'email' => 'nullable|string|email|max:100|unique:estudiantes,email,' . $estudiante->id,
            'grade' => 'nullable|string',
            'level' => 'nullable|string',
            'section' => 'nullable|string',
        ]);

        // Actualizar los datos del Estudiante
        $estudiante->update($request->all());

        // Actualizar los datos del Usuario asociado solo si han cambiado
        $userData = [];
        if ($request->input('name') !== $estudiante->user->name) {
            $userData['name'] = $request->input('name');
        }
        if ($request->input('email') !== $estudiante->user->email) {
            $userData['email'] = $request->input('email');
        }

        if (!empty($userData)) {
            $estudiante->user->update($userData);
        }

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado exitosamente.');
    }

    public function destroy(string $id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $user = User::findOrFail($estudiante->user_id);
        $user->esActivo = 0;
        $user->save();
        return redirect()->route('estudiantes.index')->with('success', 'Estudiante eliminado exitosamente.');
    }
}
