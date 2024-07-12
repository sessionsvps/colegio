<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Domicilio;
use App\Models\Estudiante;
use App\Models\Estudiante_Seccion;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controller as BaseController;
use phpDocumentor\Reflection\Types\Null_;

class EstudianteController extends BaseController
{

    public function __construct()
    {
        $this->middleware('can:estudiantes.index')->only('index');
        $this->middleware('can:estudiantes.control')->only('create','store','edit','update','destroy');
    }

    public function index()
    {
        $estudiantes = Estudiante::whereHas('user', function ($query) {
            $query->where('esActivo', 1);
        })->paginate(10);
        return view('estudiantes.index', compact('estudiantes'));
    }

    public function create()
    {
        $cursos_primaria = Curso::whereHas('niveles', function ($query) {
            $query->where('detalle', 'Primaria');
        })->get();
        $cursos_secundaria = Curso::whereHas('niveles', function ($query) {
            $query->where('detalle', 'Secundaria');
        })->get();
        $grados_primaria = Grado::where('id_nivel', 1)->get();
        $grados_secundaria = Grado::where('id_nivel', 2)->get();
        $niveles = Nivel::all();
        return view('estudiantes.create', compact('cursos_primaria', 'cursos_secundaria', 'grados_primaria', 'grados_secundaria', 'niveles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'primer_nombre' => 'required|string|max:30',
            'otros_nombres' => 'nullable|string|max:30',
            'apellido_paterno' => 'required|string|max:30',
            'apellido_materno' => 'required|string|max:30',
            'dni' => 'required|string|min:8|max:8|unique:estudiantes,dni',
            'email' => 'required|string|email|max:50|unique:estudiantes,email',
            'password' => 'required|string|min:8|max:30',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|boolean',
            'telefono_celular' => 'nullable|string|size:9',
            'año_ingreso' => 'required|integer',
            'lengua_materna' => 'required|string|max:30',
            'nacionalidad' => 'required|string|max:30',
            'departamento' => 'required|string|max:30',
            'provincia' => 'required|string|max:30',
            'distrito' => 'required|string|max:30',
            'colegio_procedencia' => 'nullable|string|max:50',
            'direccion' => 'required|string|max:100',
            'telefono_fijo' => 'nullable|string|max:30',
            'departamento_d' => 'required|string|max:30',
            'provincia_d' => 'required|string|max:30',
            'distrito_d' => 'required|string|max:30',
            'nivel' => 'required',
            'grado' => 'required',
            'seccion' => 'required',
        ]);

        // Generar un código estudiante aleatorio de 10 dígitos
        do {
            $codigoEstudiante = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (Estudiante::where('codigo_estudiante', $codigoEstudiante)->exists());

        do {
            $nroMatricula = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
        } while (Estudiante::where('nro_matricula', $nroMatricula)->exists());

        // Crear el usuario
        $user = User::create([
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')), // Hashear la contraseña
            'esActivo' => True,
        ]);

        // Asignar el rol al usuario
        $role = Role::findOrFail(3);
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

        // Crear el estudiante
        $estudiante = Estudiante::create([
            'codigo_estudiante' => $codigoEstudiante,
            'user_id' => $user->id,
            'primer_nombre' => $request->input('primer_nombre'),
            'otros_nombres' => $request->input('otros_nombres'),
            'apellido_paterno' => $request->input('apellido_paterno'),
            'apellido_materno' => $request->input('apellido_materno'),
            'dni' => $request->input('dni'),
            'email' => $request->input('email'),
            'telefono_celular' => $request->input('telefono_celular'),
            'fecha_nacimiento' => $request->input('fecha_nacimiento'),
            'sexo' => $request->input('sexo'),
            'nro_matricula' => $nroMatricula,
            'año_ingreso' => $request->input('año_ingreso'),
            'lengua_materna' => $request->input('lengua_materna'),
            'colegio_procedencia' => $request->input('colegio_procedencia'),
            'nacionalidad' => $request->input('nacionalidad'),
            'departamento' => $request->input('departamento'),
            'provincia' => $request->input('provincia'),
            'distrito' => $request->input('distrito'),
        ]);

        // LLenar la tabla intermedia
        $estudiante_seccion = Estudiante_Seccion::create([
            'codigo_estudiante' => $codigoEstudiante,
            'user_id' => $user->id,
            'año_escolar' => $request->input('año_ingreso'),
            'id_nivel' => $request->input('nivel'),
            'id_grado' => $request->input('grado'),
            'id_seccion' => $request->input('seccion'),
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante registrado exitosamente.');

    }

    public function edit(string $codigo_estudiante)
    {
        $cursos_primaria = Curso::whereHas('niveles', function ($query) {
            $query->where('detalle', 'Primaria');
        })->get();
        $cursos_secundaria = Curso::whereHas('niveles', function ($query) {
            $query->where('detalle', 'Secundaria');
        })->get();
        $grados_primaria = Grado::where('id_nivel', 1)->get();
        $grados_secundaria = Grado::where('id_nivel', 2)->get();
        $niveles = Nivel::all();
        $estudiante = Estudiante::where('codigo_estudiante', $codigo_estudiante)->firstOrFail();
        return view('estudiantes.edit', compact('estudiante', 'cursos_primaria', 'cursos_secundaria', 'grados_primaria', 'grados_secundaria', 'niveles'));
    }

    public function update(Request $request, string $codigo_estudiante)
    {
        $estudiante = Estudiante::where('codigo_estudiante', $codigo_estudiante)->firstOrFail();

        $request->validate([
            'primer_nombre' => 'required|string|max:30',
            'otros_nombres' => 'nullable|string|max:30',
            'apellido_paterno' => 'required|string|max:30',
            'apellido_materno' => 'required|string|max:30',
            'dni' => 'required|string|size:8|unique:estudiantes,dni,' . $estudiante->codigo_estudiante . ',codigo_estudiante',
            'email' => 'required|string|email|max:50|unique:estudiantes,email,' . $estudiante->codigo_estudiante . ',codigo_estudiante',
            'password' => 'nullable|string|min:8|max:30',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|boolean',
            'año_ingreso' => 'required|integer',
            'telefono_celular' => 'nullable|string|size:9',
            'lengua_materna' => 'required|string|max:30',
            'colegio_procedencia' => 'nullable|string|max:50',
            'nacionalidad' => 'required|string|max:30',
            'departamento' => 'required|string|max:30',
            'provincia' => 'required|string|max:30',
            'distrito' => 'required|string|max:30',
            'direccion' => 'required|string|max:100',
            'telefono_fijo' => 'nullable|string|max:30',
            'departamento_d' => 'required|string|max:30',
            'provincia_d' => 'required|string|max:30',
            'distrito_d' => 'required|string|max:30',
            'nivel' => 'required',
            'grado' => 'required',
            'seccion' => 'required',
        ]);

        // Actualizar los datos del Estudiante
        $estudiante->primer_nombre = $request->input('primer_nombre', $estudiante->primer_nombre);
        $estudiante->otros_nombres = $request->input('otros_nombres', $estudiante->otros_nombres);
        $estudiante->apellido_paterno = $request->input('apellido_paterno', $estudiante->apellido_paterno);
        $estudiante->apellido_materno = $request->input('apellido_materno', $estudiante->apellido_materno);
        $estudiante->dni = $request->input('dni', $estudiante->dni);
        $estudiante->fecha_nacimiento = $request->input('fecha_nacimiento', $estudiante->fecha_nacimiento);
        $estudiante->sexo = $request->input('sexo', $estudiante->sexo);
        $estudiante->año_ingreso = $request->input('año_ingreso', $estudiante->año_ingreso);
        $estudiante->telefono_celular = $request->input('telefono_celular', $estudiante->telefono_celular);
        $estudiante->lengua_materna = $request->input('lengua_materna', $estudiante->lengua_materna);
        $estudiante->colegio_procedencia = $request->input('colegio_procedencia', $estudiante->colegio_procedencia);
        $estudiante->nacionalidad = $request->input('nacionalidad', $estudiante->nacionalidad);
        $estudiante->departamento = $request->input('departamento', $estudiante->departamento);
        $estudiante->provincia = $request->input('provincia', $estudiante->provincia);
        $estudiante->distrito = $request->input('distrito', $estudiante->distrito);

        $estudiante->save();

        // Actualizar los datos del Usuario asociado solo si han cambiado
        $domicilio = Domicilio::findOrFail($estudiante->user_id);

        $domicilio->telefono_fijo = $request->input('telefono_fijo', $domicilio->telefono_fijo);
        $domicilio->departamento = $request->input('departamento_d', $domicilio->departamento);
        $domicilio->provincia = $request->input('provincia_d', $domicilio->provincia);
        $domicilio->distrito = $request->input('distrito_d', $domicilio->distrito);
        $domicilio->direccion = $request->input('direccion', $domicilio->direccion);

        $domicilio->save();

        // Actualizar solo el correo electrónico del Usuario asociado si ha cambiado
        if ($request->input('email') !== $estudiante->user->email) {
            $estudiante->user->update(['email' => $request->input('email')]);
        }
        // Si la contraseña está presente, hashearla
        if ($request->filled('password')) {
            $estudiante->user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado exitosamente.');
    }

    public function matricular()
    {
        $estudiantes = Estudiante::whereHas('user', function ($query) {
            $query->where('esActivo', 1);
        })->get();
        $niveles = Nivel::all();
        $grados_primaria = Grado::where('id_nivel', 1)->get();
        $grados_secundaria = Grado::where('id_nivel', 2)->get();
        return view('estudiantes.matricular',compact('niveles', 'grados_primaria', 'grados_secundaria', 'estudiantes'));
    }

    public function realizarMatricula(Request $request)
    {
        $codigo_estudiante = $request->codigo_estudiante;
        $estudiante = Estudiante::where('codigo_estudiante', $codigo_estudiante)->firstOrFail();
        $user = User::where('id',$estudiante->user_id)->first();

        $estudiante_seccion = Estudiante_Seccion::create([
            'codigo_estudiante' => $codigo_estudiante,
            'user_id' => $user->id,
            'año_escolar' => $request->input('año_ingreso'),
            'id_nivel' => $request->input('nivel'),
            'id_grado' => $request->input('grado'),
            'id_seccion' => $request->input('seccion'),
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante matriculado exitosamente.');
    }

    public function destroy(string $codigo_estudiante)
    {
        $estudiante = Estudiante::where('codigo_estudiante', $codigo_estudiante)->firstOrFail();
        $user = User::findOrFail($estudiante->user_id);
        $user->esActivo = 0;
        $user->save();
        return redirect()->route('estudiantes.index')->with('success', 'Estudiante eliminado exitosamente.');
    }
}
