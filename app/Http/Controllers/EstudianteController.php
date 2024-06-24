<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estudiantes = Estudiante::all();
        return view('estudiantes.index', compact('estudiantes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('estudiantes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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
        ]);

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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $estudiante = Estudiante::findOrFail($id);
        return view('estudiantes.edit', compact('estudiante'));
    }

    /**
     * Update the specified resource in storage.
     */
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


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $estudiante->delete();
        return redirect()->route('estudiantes.index')->with('success', 'Estudiante eliminado exitosamente.');
    }
}
