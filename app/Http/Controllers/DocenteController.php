<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class DocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $docentes = Docente::all();
        return view('docentes.index', compact('docentes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('docentes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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
        ]);
        
        // Crear el estudiante
        $docente = Docente::create([
            'name' => $request->input('name'),
            'dni' => $request->input('dni'),
            'email' => $request->input('email'),
            'user_id' => $user->id, // Relaciona el docente con el usuario
        ]);

        return redirect()->route('docentes.index')->with('success', 'Docente registrado exitosamente.');
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
        $docente = Docente::findOrFail($id);
        return view('docentes.edit', compact('docente'));
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $docente = Docente::findOrFail($id);
        $docente->delete();
        return redirect()->route('docentes.index')->with('success', 'Docente eliminado exitosamente.');
    }
}
