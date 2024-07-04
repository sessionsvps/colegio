<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('can:users.control');
    }

    public function index()
    {
        $users = User::where('esActivo','=', 1)->paginate(10);
        return view('users.index', compact('users'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->id,
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

        //Actualizar correo de un docente o estudiante si se modifica
        if ($user->docente) {
            if ($request->input('email') !== $user->docente->email) {
                $user->docente->update(['email' => $request->input('email')]);
            }
        } elseif ($user->estudiante) {
            if ($request->input('email') !== $user->estudiante->email) {
                $user->estudiante->update(['email' => $request->input('email')]);
            }
        }

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->esActivo = 0;
        $user->save();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
