<?php

namespace App\Http\Controllers;

use App\Mail\Credenciales;
use App\Models\Docente;
use App\Models\User;
use App\Models\Catedra;
use App\Models\Curso;
use App\Models\Domicilio;
use App\Models\Grado;
use App\Models\Nivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Estado;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;

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
        })->paginate(10);
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
            // 'password' => 'required|string|min:8|max:30',
            'sexo' => 'required|boolean',
            'telefono_celular' => 'nullable|string|size:9',
            'id_estado' => 'required|integer|exists:estados,id_estado',
            'fecha_nacimiento' => 'required|date',
            'nacionalidad' => 'required|string|max:30',
            'departamento' => 'required|string|max:30',
            'provincia' => 'required|string|max:30',
            'distrito' => 'required|string|max:30',
            'fecha_ingreso' => 'required|date',
            'direccion' => 'required|string|max:100',
            'telefono_fijo' => 'nullable|string|max:30',
            'departamento_d' => 'required|string|max:30',
            'provincia_d' => 'required|string|max:30',
            'distrito_d' => 'required|string|max:30',
        ]);

        // Generar un código docente aleatorio de 4 dígitos
        do {
            $codigoDocente = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (Docente::where('codigo_docente', $codigoDocente)->exists());

        // Generar el correo electrónico basado en la lógica dada
        $primerNombre = $request->input('primer_nombre');
        $apellidoPaterno = $request->input('apellido_paterno');
        $apellidoMaterno = $request->input('apellido_materno');

        $email = strtolower(substr($primerNombre, 0, 1) . $apellidoPaterno . substr($apellidoMaterno, 0, 1)) . '@sideral.com';
        $password = $request->input('dni');

        // Crear el usuario
        $user = User::create([
            'email' => $email,
            'password' => Hash::make($password),
            'esActivo' => true,
        ]);

        // Asignar el rol al usuario
        $role = Role::findOrFail(2);
        $user->assignRole($role);

        // Crear el domicilio
        $domicilio = Domicilio::create([
            'user_id' => $user->id,
            'telefono_fijo' => $request->input('telefono_fijo'),
            'direccion' => $request->input('direccion'),
            'departamento' => $request->input('departamento_d'),
            'provincia' => $request->input('provincia_d'),
            'distrito' => $request->input('distrito_d'),
        ]);

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
            'telefono_celular' => $request->input('telefono_celular'),
            'id_estado' => $request->input('id_estado'),
            'fecha_nacimiento' => $request->input('fecha_nacimiento'),
            'nacionalidad' => $request->input('nacionalidad'),
            'departamento' => $request->input('departamento'),
            'provincia' => $request->input('provincia'),
            'distrito' => $request->input('distrito'),
            'esTutor' => False,
            'fecha_ingreso' => $request->input('fecha_ingreso'),
        ]);

        // Enviar correo con credenciales generadas
        Mail::to($request->input('email'))->send(new Credenciales($email, $password));

        return redirect()->route('docentes.index')->with('success', 'Docente registrado exitosamente.');
    }



    public function edit(string $codigo_docente)
    {
        // Buscar el docente por las claves primarias
        $docente = Docente::where('codigo_docente', $codigo_docente)->firstOrFail();

        $estados = Estado::all();

        return view('docentes.edit', compact('docente', 'estados'));
    }

    public function update(Request $request, string $codigo_docente)
    {
        // Buscar el docente por las claves primarias
        $docente = Docente::where('codigo_docente', $codigo_docente)->firstOrFail();

        $request->validate([
            'primer_nombre' => 'required|string|max:30',
            'otros_nombres' => 'nullable|string|max:30',
            'apellido_paterno' => 'required|string|max:30',
            'apellido_materno' => 'required|string|max:30',
            'dni' => 'required|string|size:8|unique:docentes,dni,' . $docente->codigo_docente . ',codigo_docente',
            'email' => 'required|string|email|max:50|unique:docentes,email,' . $docente->codigo_docente . ',codigo_docente',
            'password' => 'nullable|string|min:8|max:30',
            'telefono_celular' => 'nullable|string|size:9',
            'sexo' => 'required|boolean',
            'id_estado' => 'required|integer|exists:estados,id_estado',
            'fecha_nacimiento' => 'required|date',
            'fecha_ingreso' => 'required|date',
            'nacionalidad' => 'required|string|max:30',
            'departamento' => 'required|string|max:30',
            'provincia' => 'required|string|max:30',
            'distrito' => 'required|string|max:30',
            'direccion' => 'required|string|max:100',
            'telefono_fijo' => 'nullable|string|max:30',
            'departamento_d' => 'required|string|max:30',
            'provincia_d' => 'required|string|max:30',
            'distrito_d' => 'required|string|max:30',
        ]);

        // Asignar los valores uno por uno
        $docente->primer_nombre = $request->input('primer_nombre',$docente->primer_nombre);
        $docente->otros_nombres = $request->input('otros_nombres',$docente->otros_nombres);
        $docente->apellido_paterno = $request->input('apellido_paterno', $docente->apellido_paterno);
        $docente->apellido_materno = $request->input('apellido_materno', $docente->apellido_materno);
        $docente->dni = $request->input('dni', $docente->dni);
        $docente->email = $request->input('email', $docente->email);
        $docente->telefono_celular = $request->input('telefono_celular', $docente->telefono_celular);
        $docente->sexo = $request->input('sexo', $docente->sexo);
        $docente->id_estado = $request->input('id_estado', $docente->id_estado);
        $docente->fecha_nacimiento = $request->input('fecha_nacimiento', $docente->fecha_nacimiento);
        $docente->fecha_ingreso = $request->input('fecha_ingreso',$docente->fecha_ingreso);
        $docente->nacionalidad = $request->input('nacionalidad', $docente->nacionalidad);
        $docente->departamento = $request->input('departamento', $docente->departamento);
        $docente->provincia = $request->input('provincia', $docente->provincia);
        $docente->distrito = $request->input('distrito', $docente->distrito);
        // Guardar los cambios
        $docente->save();

        $domicilio = Domicilio::findOrFail($docente->user_id);

        $domicilio->telefono_fijo = $request->input('telefono_fijo', $domicilio->telefono_fijo);
        $domicilio->departamento = $request->input('departamento_d', $domicilio->departamento);
        $domicilio->provincia = $request->input('provincia_d', $domicilio->provincia);
        $domicilio->distrito = $request->input('distrito_d', $domicilio->distrito);
        $domicilio->direccion = $request->input('direccion', $domicilio->direccion);

        $domicilio->save();

        // Actualizar solo el correo electrónico del Usuario asociado si ha cambiado
        if ($request->input('email') !== $docente->user->email) {
            $docente->user->update(['email' => $request->input('email')]);
        }
        // Si la contraseña está presente, hashearla
        if ($request->filled('password')) {
            $docente->user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('docentes.index')->with('success', 'Docente actualizado exitosamente.');
    }

    public function destroy(string $codigo_docente)
    {
        $docente = Docente::where('codigo_docente', $codigo_docente)->firstOrFail();
        $user = User::findOrFail($docente->user_id);
        $user->esActivo = 0;
        $user->save();
        return redirect()->route('docentes.index')->with('success', 'Docente eliminado exitosamente.');
    }
}
