<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmacionMatricula;
use App\Mail\Credenciales;
use App\Mail\CredencialesEstudiante;
use App\Models\Apoderado;
use App\Models\Asistencia;
use App\Models\Bimestre;
use App\Models\Boleta_de_nota;
use App\Models\Competencia;
use App\Models\Curso;
use App\Models\Curso_por_nivel;
use App\Models\Domicilio;
use App\Models\Estado;
use App\Models\Estudiante;
use App\Models\Estudiante_Seccion;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\Notas_por_competencia;
use App\Models\Seccion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\PseudoTypes\True_;

class EstudianteController extends BaseController
{

    public function __construct()
    {
        $this->middleware('can:Ver Estudiantes')->only('index');
        $this->middleware('can:Registrar Estudiantes')->only('create','store');
        $this->middleware('can:Editar Estudiantes')->only('edit','update');
        $this->middleware('can:Eliminar Estudiantes')->only('destroy');
        $this->middleware('can:Registrar Matriculas')->only('matricular','realizarMatricula', 'infoMatriculas', 'añadeMatriculas');
        $this->middleware('can:Editar Notas')->only('vista_docente');
    }

    public function index(Request $request)
    {
        $auth = Auth::user()->id;
        $user = User::findOrFail($auth);

        switch (true) {
            case $user->hasRole('Admin'):
            case $user->hasRole('Secretaria'):
            case $user->hasRole('Director'):

                $query = Estudiante::whereHas('user', function ($query) {
                    $query->where('esActivo', 1);
                });

                if ($request->filled('filtrar_por')) {
                    $filtrarPor = $request->input('filtrar_por');
                    if ($filtrarPor == 'matriculado') {
                        $query->whereNotNull('nro_matricula')->get();
                    } else if ($filtrarPor == 'no_matriculado') {
                        $query->where('nro_matricula', null)->get();
                    }
                }

                if ($request->filled('año_ingreso')) {
                    $query->where('año_ingreso', $request->input('año_ingreso'))->get();
                }

                if ($request->filled('buscar_por')) {
                    $buscarPor = $request->input('buscar_por');
                    $buscarValor = $request->input($buscarPor);

                    if ($buscarPor === 'codigo') {
                        $query->where('codigo_estudiante', 'like', '%' . $buscarValor . '%');
                    } elseif ($buscarPor === 'nombre') {
                        $query->where(function ($query) use ($buscarValor) {
                            $query->where(DB::raw("CONCAT(primer_nombre, ' ', otros_nombres, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscarValor . '%')
                                ->orWhere(DB::raw("CONCAT(primer_nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscarValor . '%');
                        });
                    } elseif ($buscarPor === 'dni') {
                        $query->where('dni', 'like', '%' . $buscarValor . '%');
                    } elseif ($buscarPor === 'correo') {
                        $query->whereHas('user', function ($query) use ($buscarValor) {
                            $query->where('email', 'like', '%' . $buscarValor . '%');
                        });
                    }
                }
                $estudiantes = $query->paginate(10);
                return view('estudiantes.index', compact('estudiantes'));
            case $user->hasRole('Apoderado'):
                $apoderado = Apoderado::where('user_id', $user->id)->first();
                $estudiantes = Estudiante_Seccion::where('año_escolar','2024')
                    ->whereHas('estudiante', function ($query) use ($apoderado) {
                    $query->where('id_apoderado', $apoderado->id)
                    ->whereHas('user', function ($query) {
                        $query->where('esActivo', 1);
                    });
                })->paginate(10);
                return view('estudiantes.index', compact('estudiantes'));
                break;
            default:
                break;
        }
    }

    public function vista_docente($codigo_curso, $nivel, $grado, $seccion)
    {
        $bimestres = Bimestre::all();
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
        return view('estudiantes.lista', compact('bimestres','curso', 'estudiantes', 'q_seccion'));
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
        $apoderado = Apoderado::where('dni', $request->input('dni_ap'))->first();

        if (!$apoderado){
            $validaciones = [
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
                // 'departamento' => 'required|string|max:30',
                // 'provincia' => 'required|string|max:30',
                // 'distrito' => 'required|string|max:30',
                'colegio_procedencia' => 'nullable|string|max:50',
                'direccion' => 'required|string|max:100',
                'telefono_fijo' => 'nullable|string|max:30',
                'departamento_d' => 'required|string|max:30',
                'provincia_d' => 'required|string|max:30',
                'distrito_d' => 'required|string|max:30',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
                'primer_nombre_ap' => 'required|string|max:30',
                'otros_nombres_ap' => 'nullable|string|max:30',
                'apellido_paterno_ap' => 'required|string|max:30',
                'apellido_materno_ap' => 'required|string|max:30',
                'dni_ap' => 'required|string|min:8|max:8|unique:apoderados,dni',
                'email_ap' => 'required|string|email|max:50|unique:apoderados,email',
                'fecha_nacimiento_ap' => 'required|date',
                'sexo_ap' => 'required|boolean',
                'telefono_celular_ap' => 'nullable|string|size:9',
                'photo2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            ];
        }else{
            $validaciones = [
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
                // 'departamento' => 'required|string|max:30',
                // 'provincia' => 'required|string|max:30',
                // 'distrito' => 'required|string|max:30',
                'colegio_procedencia' => 'nullable|string|max:50',
                'direccion' => 'required|string|max:100',
                'telefono_fijo' => 'nullable|string|max:30',
                'departamento_d' => 'required|string|max:30',
                'provincia_d' => 'required|string|max:30',
                'distrito_d' => 'required|string|max:30',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
                'primer_nombre_ap' => 'required|string|max:30',
                'otros_nombres_ap' => 'nullable|string|max:30',
                'apellido_paterno_ap' => 'required|string|max:30',
                'apellido_materno_ap' => 'required|string|max:30',
                'dni_ap' => 'required|string|min:8|max:8',
                'email_ap' => 'required|string|email|max:50',
                'fecha_nacimiento_ap' => 'required|date',
                'sexo_ap' => 'required|boolean',
                'telefono_celular_ap' => 'nullable|string|size:9',
                'photo2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            ]; 
        }

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

        // Generar un código estudiante aleatorio de 10 dígitos
        do {
            $codigoEstudiante = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (Estudiante::where('codigo_estudiante', $codigoEstudiante)->exists());

        // Generar el correo electrónico del estudiante
        $primerNombre = $request->input('primer_nombre');
        $apellidoPaterno = $request->input('apellido_paterno');
        $apellidoMaterno = $request->input('apellido_materno');

        $email = strtolower(substr($primerNombre, 0, 1) . $apellidoPaterno . substr($apellidoMaterno, 0, 1)) . '@sideral.com';
        $password = $request->input('dni');

        // Crear el usuario (Por defecto inactivo hasta que se matricule)
        $user = User::create([
            'email' => $email,
            'password' => Hash::make($password), // Hashear la contraseña
            'esActivo' => True
        ]);

        if ($request->hasFile('photo')) {
            $user->updateProfilePhoto($request->file('photo'));
        }

        $hayApRegistrado = True;

        if(!$apoderado){
            $hayApRegistrado = False;
            // Generar el correo electrónico del apoderado
            $primerNombreAp = $request->input('primer_nombre_ap');
            $apellidoPaternoAp = $request->input('apellido_paterno_ap');
            $apellidoMaternoAp = $request->input('apellido_materno_ap');

            $email_ap = strtolower(substr($primerNombreAp, 0, 1) . $apellidoPaternoAp . substr($apellidoMaternoAp, 0, 1)) . '@sideral.com';
            $password_ap = $request->input('dni_ap');

            // Crear el usuario (Por defecto inactivo hasta que se matricule)
            $userAp = User::create([
                'email' => $email_ap,
                'password' => Hash::make($password_ap), // Hashear la contraseña
                'esActivo' => True
            ]);

            if ($request->hasFile('photo2')) {
                $userAp->updateProfilePhoto($request->file('photo2'));
            }

            // Crear el apoderado
            $apoderado = Apoderado::create([
                'user_id' => $userAp->id,
                'primer_nombre' => $request->input('primer_nombre_ap'),
                'otros_nombres' => $request->input('otros_nombres_ap'),
                'apellido_paterno' => $request->input('apellido_paterno_ap'),
                'apellido_materno' => $request->input('apellido_materno_ap'),
                'dni' => $request->input('dni_ap'),
                'email' => $request->input('email_ap'),
                'telefono_celular' => $request->input('telefono_celular_ap'),
                'fecha_nacimiento' => $request->input('fecha_nacimiento_ap'),
                'sexo' => $request->input('sexo_ap'),
            ]);
        }

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
        if($request->nacionalidad == 'Peruano(a)') {
            $estudiante = Estudiante::create([
                'codigo_estudiante' => $codigoEstudiante,
                'user_id' => $user->id,
                'id_apoderado' => $apoderado->id,
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
        } else {
            $estudiante = Estudiante::create([
                'codigo_estudiante' => $codigoEstudiante,
                'user_id' => $user->id,
                'id_apoderado' => $apoderado->id,
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
                'departamento' => null,
                'provincia' => null,
                'distrito' => null,
            ]);
        }
        

        // Enviar correo con credenciales generadas
        Mail::to($request->input('email'))->send(new Credenciales($email, $password,true));
        if(!$hayApRegistrado){
            Mail::to($request->input('email_ap'))->send(new Credenciales($email_ap, $password_ap, false));
        }

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

        $validaciones = [
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

        // Actualizar datos del estudiante
        if($request->nacionalidad == 'Peruano(a)') {
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
        } else {
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
                'departamento' => null,
                'provincia' => null,
                'distrito' => null,
            ]);
        }
        

        if ($request->hasFile('photo')) {
            $estudiante->user->updateProfilePhoto($request->file('photo'));
        }

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

    public function matricular(Request $request)
    {
        // Inicializar la consulta base para los estudiantes activos
        $query = Estudiante::whereHas('user', function($query) {
            $query->where('esActivo', 1);
        });

        // Verificar si hay algún filtro de búsqueda
        if($request->filled('buscar_por_mat')) {
            $buscarPor = $request->input('buscar_por_mat');
            $buscarValor = $request->input($buscarPor);

            if ($buscarPor === 'codigo') {
                $query->where('codigo_estudiante', 'like', '%'.$buscarValor.'%');
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

        $estudiantes = $query->paginate(10);
        $niveles = Nivel::all();
        $grados_primaria = Grado::where('id_nivel', 1)->get();
        $grados_secundaria = Grado::where('id_nivel', 2)->get();
        return view('estudiantes.matricular', compact('niveles', 'grados_primaria', 'grados_secundaria', 'estudiantes'));
    }

    public function infoMatriculas($codigo_estudiante) {
        $estudiante = Estudiante::where('codigo_estudiante', $codigo_estudiante)->first();
        $matriculas = $estudiante->estudiantes_seccion;
        return view('estudiantes.info', compact('estudiante', 'matriculas'));
    }

    public function añadeMatriculas($codigo_estudiante) {
        $estudiante = Estudiante::where('codigo_estudiante', $codigo_estudiante)->first();
        $niveles = Nivel::all();
        $grados_primaria = Grado::where('id_nivel', 1)->get();
        $grados_secundaria = Grado::where('id_nivel', 2)->get();
        return view('estudiantes.añade-matricula', compact('estudiante', 'niveles', 'grados_primaria', 'grados_secundaria'));
    }

    public function realizarMatricula(Request $request, $codigo_estudiante)
    {
        $request->validate([
            'año_escolar' => 'required|integer',
            'nivel' => 'required',
            'grado' => 'required',
            'seccion' => 'required',
        ]);

        // $codigo_estudiante = $request->codigo_estudiante;
        $estudiante = Estudiante::where('codigo_estudiante', $codigo_estudiante)->firstOrFail();
        $matricula_previa = Estudiante_Seccion::where('codigo_estudiante', $codigo_estudiante)
            ->where('año_escolar', $request->input('año_escolar'))->first();
        if($matricula_previa) {
            return redirect()->back()->withErrors(['error' => 'El estudiante presenta una matrícula previa para el año escolar ' . $request->input('año_escolar') . ' en el grado ' . $matricula_previa->seccion->grado->detalle . ' ' . $matricula_previa->seccion->detalle . ' de ' . $matricula_previa->seccion->grado->nivel->detalle. '.']);
        }

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

        $user = User::findOrFail($estudiante->user_id);
        $apoderado = Apoderado::findOrFail($estudiante->id_apoderado);
        $user_ap = User::findOrFail($apoderado->user_id);

        if($user->getRoleNames()->isEmpty()) {
            $role = Role::findOrFail(4);
            $user->assignRole($role);
        }
        if ($user_ap->getRoleNames()->isEmpty()) {
            $role = Role::findOrFail(3);
            $user_ap->assignRole($role);
        }

        // Enviar correo de confirmacion de matricula
        Mail::to($estudiante->email)->send(new ConfirmacionMatricula());

        return redirect()->route('estudiantes.info-matriculas', $estudiante->codigo_estudiante)->with('success', 'Estudiante matriculado exitosamente.');
    }

    public function eliminarMatricula($codigo_estudiante, $nivel, $grado, $seccion, $año) {
        // $matricula = Estudiante_Seccion::where('codigo_estudiante', $codigo_estudiante)
        //                                ->where('id_nivel', $nivel)
        //                                ->where('id_grado', $grado)
        //                                ->where('id_seccion', $seccion)
        //                                ->where('año_escolar', $año)
        //                                ->first();
        // if ($matricula) {
        //     $matricula->esActivo = 0;
        //     $matricula->save();
        //     return redirect()->route('estudiantes.info-matriculas', $codigo_estudiante)->with('success', 'Matrícula eliminada exitosamente.');
        // } else {
        //     return redirect()->route('estudiantes.info-matriculas', $codigo_estudiante)->with('error', 'Matrícula no encontrada.');
        // }
    }

    public function destroy(string $codigo_estudiante)
    {
        $estudiante = Estudiante::where('codigo_estudiante', $codigo_estudiante)->firstOrFail();
        $apoderado  = Apoderado::findOrFail($estudiante->id_apoderado);
        $user = User::findOrFail($estudiante->user_id);
        $userAp = User::findOrFail($apoderado->user_id);
        $user->esActivo = 0;
        $userAp->esActivo = 0;
        $user->save();
        $userAp->save();
        return redirect()->route('estudiantes.index')->with('success', 'Estudiante eliminado exitosamente.');
    }
}
