<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('can:Ver Usuarios')->only('index');
        $this->middleware('can:Editar Usuarios')->only('edit','update');
        $this->middleware('can:Eliminar Usuarios')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = User::where('esActivo', 1);
        if ($request->filled('rol')) {
            $query->role($request->input('rol'));
        }
        if ($request->filled('nombre')) {
            $name = $request->input('nombre');
            $query->where(function ($query) use ($name) {
                $query->whereHas('docente', function ($query) use ($name) {
                    $query->where(DB::raw("CONCAT(primer_nombre, ' ', otros_nombres, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $name . '%')->orWhere(DB::raw("CONCAT(primer_nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $name . '%');
                })->orWhereHas('estudiante', function ($query) use ($name) {
                    $query->where(DB::raw("CONCAT(primer_nombre, ' ', otros_nombres, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $name . '%')->orWhere(DB::raw("CONCAT(primer_nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $name . '%');
                })->orWhereHas('director', function ($query) use ($name) {
                    $query->where(DB::raw("CONCAT(primer_nombre, ' ', otros_nombres, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $name . '%')->orWhere(DB::raw("CONCAT(primer_nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $name . '%');
                })->orWhereHas('secretaria', function ($query) use ($name) {
                    $query->where(DB::raw("CONCAT(primer_nombre, ' ', otros_nombres, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $name . '%')->orWhere(DB::raw("CONCAT(primer_nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $name . '%');
                });
        });
        }
        
        $users = $query->paginate(10);
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
