<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controller as BaseController;


class DocenteController extends BaseController
{

    public function __construct()
    {
        $this->middleware('can:docentes.index')->only('index');
        $this->middleware('can:docentes.control')->only('create', 'store', 'edit', 'update', 'destroy');
    }

    public function index()
    {
        $docentes = Docente::whereHas('user', function ($query) {
            $query->where('esActivo', 1);
        })->get();
        return view('docentes.index', compact('docentes'));
    }

    public function create()
    {
        return view('docentes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'dni' => 'required|string|min:8|max:8|unique:docentes,dni',
            'email' => 'required|string|email|max:100|unique:docentes,email',
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
        $role = Role::findOrFail(2);
        $user->assignRole($role);
        
        // Crear el estudiante
        $docente = Docente::create([
            'name' => $request->input('name'),
            'dni' => $request->input('dni'),
            'email' => $request->input('email'),
            'user_id' => $user->id, // Relaciona el docente con el usuario
        ]);

        return redirect()->route('docentes.index')->with('success', 'Docente registrado exitosamente.');
    }

    public function edit(string $id)
    {
        $docente = Docente::findOrFail($id);
        return view('docentes.edit', compact('docente'));
    }

    public function update(Request $request, string $id)
    {
        $docente = Docente::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:100',
            'dni' => 'nullable|string|min:8|max:8|unique:docentes,dni,' . $docente->id,
            'email' => 'nullable|string|email|max:100|unique:docentes,email,' . $docente->id,
        ]);

        $docente->update($request->all());

        // Actualizar los datos del Usuario asociado solo si han cambiado
        $userData = [];
        if ($request->input('name') !== $docente->user->name) {
            $userData['name'] = $request->input('name');
        }
        if ($request->input('email') !== $docente->user->email) {
            $userData['email'] = $request->input('email');
        }

        if (!empty($userData)) {
            $docente->user->update($userData);
        }

        return redirect()->route('docentes.index')->with('success', 'Docente actualizado exitosamente.');
    }

    public function destroy(string $id)
    {
        $docente = Docente::findOrFail($id);
        $user = User::findOrFail($docente->user_id);
        $user->esActivo = 0;
        $user->save();
        return redirect()->route('docentes.index')->with('success', 'Docente eliminado exitosamente.');
    }
}
