<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('can:users.control');
    }

    public function index()
    {
        $users = User::where('esActivo','=', 1)->get();
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

        // get the names of the user's roles
        $roles = $user->getRoleNames(); // Returns a collection

        // Actualizar los datos del Docente asociado solo si han cambiado
        $data = [];
        if ($roles[0]=='Docente'){
            if ($request->input('name') !== $user->docente->name) {
                $data['name'] = $request->input('name');
            }
            if ($request->input('email') !== $user->docente->email) {
                $data['email'] = $request->input('email');
            }
            if (!empty($data)) {
                $user->docente->update($data);
            }
        }else{
            if ($request->input('name') !== $user->estudiante->name) {
                $data['name'] = $request->input('name');
            }
            if ($request->input('email') !== $user->estudiante->email) {
                $data['email'] = $request->input('email');
            }
            if (!empty($data)) {
                $user->estudiante->update($data);
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
