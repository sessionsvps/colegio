<?php

namespace App\Http\Controllers;

use App\Mail\Credenciales;
use App\Models\Docente;
use App\Models\User;
use App\Models\Domicilio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Estado;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\PseudoTypes\False_;

class DocenteController extends BaseController
{

    public function __construct()
    {
        $this->middleware('can:Ver Docentes')->only('index');
        $this->middleware('can:Registrar Docentes')->only('create','store');
        $this->middleware('can:Editar Docentes')->only('edit','update');
        $this->middleware('can:Eliminar Docentes')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Docente::whereHas('user', function ($query) {
            $query->where('esActivo', 1);
        });

        if ($request->filled('buscar_por')) {
            $buscarPor = $request->input('buscar_por');
            $buscarValor = $request->input($buscarPor);

            if ($buscarPor === 'codigo') {
                $query->where('codigo_docente', 'like', '%'.$buscarValor.'%');
            } elseif ($buscarPor === 'nombre') {
                $query->where(function ($query) use ($buscarValor) {
                    $query->where(DB::raw("CONCAT(primer_nombre, ' ', otros_nombres, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscarValor . '%')
                        ->orWhere(DB::raw("CONCAT(primer_nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscarValor . '%');
                });
            } elseif ($buscarPor === 'dni') {
                $query->where('dni', 'like', '%'.$buscarValor.'%');
            } elseif ($buscarPor === 'correo') {
                $query->whereHas('user', function ($query) use ($buscarValor) {
                    $query->where('email', 'like', '%' . $buscarValor . '%');
                });
            }
        }

        $docentes = $query->paginate(10);
        return view('docentes.index', compact('docentes'));
    }

    public function create()
    {
        $estados = Estado::all();
        return view('docentes.create', compact('estados'));
    }

    public function store(Request $request)
    {
        $validaciones = [
            'primer_nombre' => 'required|string|max:30',
            'otros_nombres' => 'nullable|string|max:30',
            'apellido_paterno' => 'required|string|max:30',
            'apellido_materno' => 'required|string|max:30',
            'dni' => 'required|string|size:8|unique:docentes,dni',
            'email' => 'required|string|email|max:50|unique:docentes,email',
            'sexo' => 'required|boolean',
            'telefono_celular' => 'nullable|string|size:9',
            'id_estado' => 'required|integer|exists:estados,id_estado',
            'fecha_nacimiento' => 'required|date',
            'nacionalidad' => 'required|string|max:30',
            // 'departamento' => 'required|string|max:30',
            // 'provincia' => 'required|string|max:30',
            // 'distrito' => 'required|string|max:30',
            'fecha_ingreso' => 'required|date',
            'direccion' => 'required|string|max:100',
            'telefono_fijo' => 'nullable|string|max:30',
            'departamento_d' => 'required|string|max:30',
            'provincia_d' => 'required|string|max:30',
            'distrito_d' => 'required|string|max:30',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024'
        ];

        if ($request->nacionalidad === 'Extranjero(a)') {
            // Si la nacionalidad es extranjera, los campos son opcionales
            $validaciones['departamento'] = 'nullable|string|max:30';
            $validaciones['provincia'] = 'nullable|string|max:30';
            $validaciones['distrito'] = 'nullable|string|max:30';
        } else {
            // Si la nacionalidad no es extranjera, los campos son obligatorios
            $validaciones['departamento'] = 'required|string|max:30';
            $validaciones['provincia'] = 'required|string|max:30';
            $validaciones['distrito'] = 'required|string|max:30';
        }

        $request->validate($validaciones);

        // Generar un código docente aleatorio de 4 dígitos
        do {
            $codigoDocente = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (Docente::where('codigo_docente', $codigoDocente)->exists());

        // Generar el correo electrónico basado
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

        if ($request->hasFile('photo')) {
            $user->updateProfilePhoto($request->file('photo'));
        }

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
        if($request->nacionalidad == 'Peruano(a)'){
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
        } else {
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
                'departamento' => null,
                'provincia' => null,
                'distrito' => null,
                'esTutor' => False,
                'fecha_ingreso' => $request->input('fecha_ingreso'),
            ]);
        }

        // Enviar correo con credenciales generadas
        Mail::to($request->input('email'))->send(new Credenciales($email, $password,false));

        return redirect()->route('docentes.index')->with('success', 'Docente registrado exitosamente.');
    }

    public function edit(string $codigo_docente)
    {
        $docente = Docente::where('codigo_docente', $codigo_docente)->firstOrFail();
        $estados = Estado::all();
        return view('docentes.edit', compact('docente', 'estados'));
    }

    public function update(Request $request, string $codigo_docente)
    {
        $docente = Docente::where('codigo_docente', $codigo_docente)->firstOrFail();
        $domicilio = Domicilio::findOrFail($docente->user_id);

        $validaciones = [
            'primer_nombre' => 'required|string|max:30',
            'otros_nombres' => 'nullable|string|max:30',
            'apellido_paterno' => 'required|string|max:30',
            'apellido_materno' => 'required|string|max:30',
            'dni' => 'required|string|size:8|unique:docentes,dni,' . $docente->codigo_docente . ',codigo_docente',
            'email' => 'required|string|email|max:50|unique:docentes,email,' . $docente->codigo_docente . ',codigo_docente',
            'telefono_celular' => 'nullable|string|size:9',
            'sexo' => 'required|boolean',
            'id_estado' => 'required|integer|exists:estados,id_estado',
            'fecha_nacimiento' => 'required|date',
            'fecha_ingreso' => 'required|date',
            'nacionalidad' => 'required|string|max:30',
            // 'departamento' => 'required|string|max:30',
            // 'provincia' => 'required|string|max:30',
            // 'distrito' => 'required|string|max:30',
            'direccion' => 'required|string|max:100',
            'telefono_fijo' => 'nullable|string|max:30',
            'departamento_d' => 'required|string|max:30',
            'provincia_d' => 'required|string|max:30',
            'distrito_d' => 'required|string|max:30',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024'
        ];

        if ($request->nacionalidad === 'Extranjero(a)') {
            // Si la nacionalidad es extranjera, los campos son opcionales
            $validaciones['departamento'] = 'nullable|string|max:30';
            $validaciones['provincia'] = 'nullable|string|max:30';
            $validaciones['distrito'] = 'nullable|string|max:30';
        } else {
            // Si la nacionalidad no es extranjera, los campos son obligatorios
            $validaciones['departamento'] = 'required|string|max:30';
            $validaciones['provincia'] = 'required|string|max:30';
            $validaciones['distrito'] = 'required|string|max:30';
        }

        $request->validate($validaciones);

        // Actualizar datos del docente
        if($request->nacionalidad == 'Peruano(a)') {
            $docente->update([
                'primer_nombre' => $request->primer_nombre,
                'otros_nombres' => $request->otros_nombres,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'dni' => $request->dni,
                'email' => $request->email,
                'telefono_celular' => $request->telefono_celular,
                'sexo' => $request->sexo,
                'id_estado' => $request->id_estado,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'fecha_ingreso' => $request->fecha_ingreso,
                'nacionalidad' => $request->nacionalidad,
                'departamento' => $request->departamento,
                'provincia' => $request->provincia,
                'distrito' => $request->distrito,
            ]);
        } else {
            $docente->update([
                'primer_nombre' => $request->primer_nombre,
                'otros_nombres' => $request->otros_nombres,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'dni' => $request->dni,
                'email' => $request->email,
                'telefono_celular' => $request->telefono_celular,
                'sexo' => $request->sexo,
                'id_estado' => $request->id_estado,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'fecha_ingreso' => $request->fecha_ingreso,
                'nacionalidad' => $request->nacionalidad,
                'departamento' => null,
                'provincia' => null,
                'distrito' => null,
            ]);
        }

        if ($request->hasFile('photo')) {
            $docente->user->updateProfilePhoto($request->file('photo'));
        }

        // Actualizar datos del domicilio
        $domicilio->update([
            'direccion' => $request->direccion,
            'telefono_fijo' => $request->telefono_fijo,
            'departamento' => $request->departamento_d,
            'provincia' => $request->provincia_d,
            'distrito' => $request->distrito_d
        ]);

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
