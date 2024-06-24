<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|string|email|max:100|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|max:30',
        ]);

        // Recolectar datos del request
        $data = $request->all();

        // Si la contraseña está presente, hashearla
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            // Eliminar la contraseña de los datos a actualizar si no se proporciona
            unset($data['password']);
        }

        // Actualizar el usuario
        $user->update($data);

        // // Actualizar los datos del Docente asociado solo si han cambiado
        // $docenteData = [];
        // if ($request->input('name') !== $user->docente->name) {
        //     $docenteData['name'] = $request->input('name');
        // }
        // if ($request->input('email') !== $user->docente->email) {
        //     $docenteData['email'] = $request->input('email');
        // }

        // if (!empty($docenteData)) {
        //     $user->docente->update($docenteData);
        // }

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
