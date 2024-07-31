<?php

namespace App\Http\Controllers;

use App\Mail\CredencialesEstudiante;
use App\Models\Asistencia;
use App\Models\Boleta_de_nota;
use App\Models\Competencia;
use App\Models\Curso;
use App\Models\Curso_por_nivel;
use App\Models\Domicilio;
use App\Models\Estudiante;
use App\Models\Estudiante_Seccion;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\Notas_por_competencia;
use App\Models\Seccion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;

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

    public function vista_docente($codigo_curso, $nivel, $grado, $seccion) 
    {
        // dd($codigo_curso, $nivel, $grado, $seccion);
        $curso = Curso::where('codigo_curso', $codigo_curso)
                      ->where('esActivo',1)
                      ->first();

        $q_seccion = Seccion::where('id_nivel', $nivel)
                          ->where('id_grado', $grado)
                          ->where('id_seccion', $seccion)
                          ->first();
        $estudiantes = Estudiante_Seccion::where('año_escolar', Carbon::now()->year)
                                                ->where('id_nivel', $nivel)
                                                ->where('id_grado', $grado)
                                                ->where('id_seccion', $seccion)
                                                ->whereDoesntHave('exoneraciones', function($query) use ($codigo_curso) {
                                                    $query->where('codigo_curso', $codigo_curso);
                                                })
                                                ->get();
        // dd($estudiantes);
        return view('estudiantes.lista', compact('curso', 'estudiantes', 'q_seccion'));
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
            'distrito_d' => 'required|string|max:30'
        ]);

        // Generar un código estudiante aleatorio de 10 dígitos
        do {
            $codigoEstudiante = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (Estudiante::where('codigo_estudiante', $codigoEstudiante)->exists());

        // Generar el correo electrónico
        $primerNombre = $request->input('primer_nombre');
        $apellidoPaterno = $request->input('apellido_paterno');
        $apellidoMaterno = $request->input('apellido_materno');

        $email = strtolower(substr($primerNombre, 0, 1) . $apellidoPaterno . substr($apellidoMaterno, 0, 1)) . '@sideral.com';
        $password = $request->input('dni');

        // Crear el usuario (Por defecto inactivo hasta que se matricule)
        $user = User::create([
            'email' => $email,
            'password' => Hash::make($password), // Hashear la contraseña
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
            'nro_matricula' => null,
            'año_ingreso' => $request->input('año_ingreso'),
            'lengua_materna' => $request->input('lengua_materna'),
            'colegio_procedencia' => $request->input('colegio_procedencia'),
            'nacionalidad' => $request->input('nacionalidad'),
            'departamento' => $request->input('departamento'),
            'provincia' => $request->input('provincia'),
            'distrito' => $request->input('distrito'),
        ]);

        // Enviar correo con credenciales generadas
        Mail::to($request->input('email'))->send(new CredencialesEstudiante($email, $password));

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante registrado exitosamente.');
    }

    public function edit(string $codigo_estudiante)
    {
        $estudiante = Estudiante::where('codigo_estudiante', $codigo_estudiante)->firstOrFail();
        return view('estudiantes.edit', compact('estudiante'));
    }

    public function update(Request $request, string $codigo_estudiante)
    {
        $estudiante = Estudiante::where('codigo_estudiante', $codigo_estudiante)->firstOrFail();
        $domicilio = Domicilio::findOrFail($estudiante->user_id);

        $request->validate([
            'primer_nombre' => 'required|string|max:30',
            'otros_nombres' => 'nullable|string|max:30',
            'apellido_paterno' => 'required|string|max:30',
            'apellido_materno' => 'required|string|max:30',
            'dni' => 'required|string|size:8|unique:estudiantes,dni,' . $estudiante->codigo_estudiante . ',codigo_estudiante',
            'email' => 'required|string|email|max:50|unique:estudiantes,email,' . $estudiante->codigo_estudiante . ',codigo_estudiante',
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
            'distrito_d' => 'required|string|max:30'
        ]);

        // Actualizar datos del estudiante
        $estudiante->update([
            'primer_nombre' => $request->primer_nombre,
            'otros_nombres' => $request->otros_nombres,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'dni' => $request->dni,
            'email' => $request->email,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'sexo' => $request->sexo,
            'año_ingreso' => $request->año_ingreso,
            'telefono_celular' => $request->telefono_celular,
            'lengua_materna' => $request->lengua_materna,
            'colegio_procedencia' => $request->colegio_procedencia,
            'nacionalidad' => $request->nacionalidad,
            'departamento' => $request->departamento,
            'provincia' => $request->provincia,
            'distrito' => $request->distrito,
        ]);

        // Actualizar datos del domicilio
        $domicilio->update([
            'direccion' => $request->direccion,
            'telefono_fijo' => $request->telefono_fijo,
            'departamento' => $request->departamento_d,
            'provincia' => $request->provincia_d,
            'distrito' => $request->distrito_d
        ]);

        return redirect()->route('estudiantes.index')->with('success', 'Estudiante actualizado exitosamente.');
    }

    public function matricular()
    {
        $estudiantes = Estudiante::whereHas('user', function ($query) {
            $query->where('esActivo', 1);
            $query->where('nro_matricula', null);
        })->get();
        $niveles = Nivel::all();
        $grados_primaria = Grado::where('id_nivel', 1)->get();
        $grados_secundaria = Grado::where('id_nivel', 2)->get();
        return view('estudiantes.matricular',compact('niveles', 'grados_primaria', 'grados_secundaria', 'estudiantes'));
    }

    public function realizarMatricula(Request $request)
    {

        $request->validate([
            'codigo_estudiante' => 'required',
            'año_escolar' => 'required|integer',
            'nivel' => 'required',
            'grado' => 'required',
            'seccion' => 'required',
        ]);

        $codigo_estudiante = $request->codigo_estudiante;
        $estudiante = Estudiante::where('codigo_estudiante', $codigo_estudiante)->firstOrFail();

        if ($estudiante->nro_matricula == null){
            do {
                $nroMatricula = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
            } while (Estudiante::where('nro_matricula', $nroMatricula)->exists());

            $estudiante->nro_matricula = $nroMatricula;
            $estudiante->save();
        }

        $estudiante_seccion = Estudiante_Seccion::create([
            'codigo_estudiante' => $codigo_estudiante,
            'user_id' => $estudiante->user_id,
            'año_escolar' => $request->input('año_escolar'),
            'id_nivel' => $request->input('nivel'),
            'id_grado' => $request->input('grado'),
            'id_seccion' => $request->input('seccion'),
        ]);

        $boleta_nota = Boleta_de_nota::create([
            'codigo_estudiante' => $codigo_estudiante,
            'user_id' => $estudiante->user_id,
            'año_escolar' => $request->input('año_escolar'),
            'codigo_modular' => '1554526057',
        ]);

        for ($i = 1; $i <= 4; $i++) {
            Asistencia::create([
                'codigo_estudiante' => $codigo_estudiante,
                'user_id' => $estudiante->user_id,
                'año_escolar' => $request->input('año_escolar'),
                'id_bimestre' => $i,
                'inasistencias_justificadas' => 0,
                'inasistencias_injustificadas' => 0,
                'tardanzas_justificadas' => 0,
                'tardanzas_injustificadas' => 0,
            ]);
        }

        $cursos = Curso_por_nivel::where('id_nivel', $request->input('nivel'))->get();
        foreach($cursos as $curso){
            foreach($curso->curso->competencias as $competencia){
                for ($i = 1; $i <= 4; $i++) {
                    Notas_por_competencia::create([
                        'codigo_estudiante' => $codigo_estudiante,
                        'user_id' => $estudiante->user_id,
                        'año_escolar' => $request->input('año_escolar'),
                        'id_bimestre' => $i,
                        'codigo_curso' => $competencia->codigo_curso,
                        'orden' => $competencia->orden,
                        'nivel_logro' => null,
                        'exoneracion' => false,
                    ]);
                }
            }
        }

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
