<?php

namespace App\Http\Controllers;

use App\Models\Bimestre;
use App\Models\Curso;
use App\Models\Curso_por_nivel;
use App\Models\Estudiante_Seccion;
use App\Models\Exoneracion;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\Notas_por_competencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExoneracionController extends BaseController
{

    public function __construct()
    {
        $this->middleware('can:Ver Exoneraciones')->only('index');
        $this->middleware('can:Editar Exoneraciones')->only('edit', 'update');
    }
    public function index(Request $request)
    {
        $estudiante = null;
        $exoneraciones = collect();

        $auth = Auth::user()->id;
        $user = User::findOrFail($auth);

        switch (true) {
            case $user->hasRole('Admin'):
            case $user->hasRole('Secretaria'):
            case $user->hasRole('Director'):
                $niveles = Nivel::all();
                $grados_primaria = Grado::where('id_nivel', 1)->get();
                $grados_secundaria = Grado::where('id_nivel', 2)->get();

                $año_escolar = $request->input('año_escolar');
                $query = Estudiante_Seccion::whereHas('estudiante.user', function ($query) {
                    $query->where('esActivo', 1);
                })->where('año_escolar', $año_escolar);

                if ($request->filled('nivel')) {
                    $query->where('id_nivel', $request->nivel);
                }
                if ($request->filled('grado')) {
                    $query->where('id_grado', $request->grado);
                }
                if ($request->filled('seccion')) {
                    $query->where('id_seccion', $request->seccion);
                }

                if ($request->filled('buscar_por')) {
                    $buscarPor = $request->input('buscar_por');
                    $buscarValor = $request->input($buscarPor);

                    if ($buscarPor === 'codigo') {
                        $query->whereHas('estudiante', function ($query) use ($buscarValor) {
                            $query->where('codigo_estudiante', $buscarValor);
                        });
                    } elseif ($buscarPor === 'nombre') {
                        $query->whereHas('estudiante', function ($query) use ($buscarValor) {
                            $query->where(function ($query) use ($buscarValor) {
                                $query->where(DB::raw("CONCAT(primer_nombre, ' ', otros_nombres, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscarValor . '%')
                                    ->orWhere(DB::raw("CONCAT(primer_nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscarValor . '%');
                            });
                        });
                    } elseif ($buscarPor === 'dni') {
                        $query->whereHas('estudiante', function ($query) use ($buscarValor) {
                            $query->where('dni', $buscarValor);
                        });
                    } elseif ($buscarPor === 'correo') {
                        $query->whereHas('estudiante.user', function ($query) use ($buscarValor) {
                            $query->where('email', 'like', '%' . $buscarValor . '%');
                        });
                    }
                }

                $estudiantes = $query->get();
                $codigo_estudiantes = $estudiantes->pluck('codigo_estudiante');
                $exoneraciones = Exoneracion::where('año_escolar', $request->input('año_escolar'))
                    ->whereIn('codigo_estudiante', $codigo_estudiantes)
                    ->get();
                return view('exoneraciones.index', compact('estudiantes', 'exoneraciones', 'niveles', 'grados_primaria', 'grados_secundaria'));
                break;
            case $user->hasRole('Estudiante_Matriculado'):
                $estudiante = Estudiante_Seccion::where('user_id', $user->id)
                    ->where('año_escolar','2024')->first();
                $cursos = Curso_por_nivel::where('id_nivel', $estudiante->id_nivel)->get();
                $codigoCursos = $cursos->pluck('codigo_curso');
                $exoneraciones = Exoneracion::where('codigo_estudiante', $estudiante->codigo_estudiante)
                    ->where('año_escolar', $estudiante->año_escolar)
                    ->whereIn('codigo_curso', $codigoCursos)
                    ->get();
                return view('exoneraciones.index', compact('estudiante', 'exoneraciones'));
                break;
            default:
                break;
        }      

    }

    public function edit(string $codigo_estudiante, string $año_escolar)
    {
        $estudiante = Estudiante_Seccion::where('codigo_estudiante', $codigo_estudiante)->first();
        $exoneraciones = Exoneracion::where('codigo_estudiante', $codigo_estudiante)
            ->where('año_escolar', $año_escolar)->get();
        $cursos_exonerables = Curso::whereIn('codigo_curso', ['3965', '5350'])->get();
        return view('exoneraciones.edit', compact('estudiante', 'exoneraciones','cursos_exonerables'));
    }

    public function update(Request $request, string $codigo_estudiante, string $año_escolar)
    {
        $estudiante = Estudiante_Seccion::where('codigo_estudiante', $codigo_estudiante)->first();
        $cursos_exonerados = $request->input('cursos_exonerados', []);

        // Obtener los cursos exonerables existentes
        $exoneraciones_existentes = Exoneracion::where('codigo_estudiante', $codigo_estudiante)
            ->where('año_escolar', $año_escolar)
            ->get();

        // Crear nuevas exoneraciones
        foreach ($cursos_exonerados as $curso_codigo) {
            if (!$exoneraciones_existentes->contains('codigo_curso', $curso_codigo)) {
                Exoneracion::create([
                    'user_id' => $estudiante->user_id,
                    'codigo_estudiante' => $codigo_estudiante,
                    'codigo_curso' => $curso_codigo,
                    'año_escolar' => $año_escolar
                ]);
                Notas_por_competencia::where('codigo_estudiante', $codigo_estudiante)
                    ->where('codigo_curso', $curso_codigo)
                    ->where('año_escolar', $año_escolar)
                    ->update(['exoneracion' => 1]);
            }
        }

        // Eliminar exoneraciones desmarcadas
        foreach ($exoneraciones_existentes as $exoneracion) {
            if (!in_array($exoneracion->codigo_curso, $cursos_exonerados)) {
                $exoneracion->delete();
                // Actualizar las notas de competencia
                Notas_por_competencia::where('codigo_estudiante', $codigo_estudiante)
                ->where('codigo_curso', $exoneracion->codigo_curso)
                ->where('año_escolar', $año_escolar)
                ->update(['exoneracion' => 0]);
            }
        }

        return redirect()->route('exoneraciones.index')->with('success', 'Exoneraciones actualizadas correctamente.');
    }

}
