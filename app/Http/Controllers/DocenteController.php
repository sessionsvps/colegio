<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Estado;
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
        $estados = Estado::all();
        return view('docentes.create', compact('estados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'primer_nombre' => 'required|string|max:30',
            'otros_nombres' => 'nullable|string|max:30',
            'apellido_paterno' => 'required|string|max:30',
            'apellido_materno' => 'required|string|max:30',
            'dni' => 'required|string|size:8|unique:docentes,dni',
            'email' => 'required|string|email|max:50|unique:docentes,email',
            'password' => 'required|string|min:8|max:30',
            'sexo' => 'required|boolean',
            'id_estado' => 'required|integer|exists:estados,id_estado',
        ]);

        // Generar un código docente aleatorio de 4 dígitos
        do {
            $codigoDocente = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (Docente::where('codigo_docente', $codigoDocente)->exists());

        // Crear el usuario
        $user = User::create([
            'name' => $request->input('primer_nombre'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'esActivo' => true,
        ]);

        // Asignar el rol al usuario
        $role = Role::findOrFail(2);
        $user->assignRole($role);

        // Crear el docente
        $docente = Docente::create([
            'codigo_docente' => $codigoDocente,
            'user_id' => $user->id,
            'primer_nombre' => $request->input('primer_nombre'),
            'otros_nombres' => $request->input('otros_nombres'),
            'apellido_paterno' => $request->input('apellido_paterno'),
            'apellido_materno' => $request->input('apellido_materno'),
            'dni' => $request->input('dni'),
            'email' => $request->input('email'),
            'sexo' => $request->input('sexo'),
            'id_estado' => $request->input('id_estado'),
        ]);

        return redirect()->route('docentes.index')->with('success', 'Docente registrado exitosamente.');
    }

    public function edit(string $codigo_docente)
    {
        $docente = Docente::findOrFail($codigo_docente);
        $estados = Estado::all();
        return view('docentes.edit', compact('docente', 'estados'));
    }

    public function update(Request $request, string $codigo_docente)
    {
        $docente = Docente::findOrFail($codigo_docente);

        $request->validate([
            'primer_nombre' => 'nullable|string|max:30',
            'otros_nombres' => 'nullable|string|max:30',
            'apellido_paterno' => 'nullable|string|max:30',
            'apellido_materno' => 'nullable|string|max:30',
            'dni' => 'nullable|string|size:8|unique:docentes,dni,' . $docente->codigo_docente . ',codigo_docente',
            'email' => 'nullable|string|email|max:50|unique:docentes,email,' . $docente->codigo_docente . ',codigo_docente',
            'sexo' => 'nullable|boolean',
            'id_estado' => 'nullable|integer|exists:estados,id_estado',
        ]);

        $docente->update($request->all());

        // Actualizar solo el correo electrónico del Usuario asociado si ha cambiado
        if ($request->input('email') !== $docente->user->email) {
            $docente->user->update(['email' => $request->input('email')]);
        }

        return redirect()->route('docentes.index')->with('success', 'Docente actualizado exitosamente.');
    }

    public function destroy(string $codigo_docente)
    {
        $docente = Docente::findOrFail($codigo_docente);
        $user = User::findOrFail($docente->user_id);
        $user->esActivo = 0;
        $user->save();
        return redirect()->route('docentes.index')->with('success', 'Docente eliminado exitosamente.');
    }
}
