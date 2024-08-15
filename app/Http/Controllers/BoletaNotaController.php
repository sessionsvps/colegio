<?php

namespace App\Http\Controllers;

use App\Models\Apoderado;
use App\Models\Bimestre;
use App\Models\Curso;
use App\Models\Curso_por_nivel;
use App\Models\Estudiante_Seccion;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\Notas_por_competencia;
use App\Models\Seccion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BoletaNotaController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('can:Ver Notas')->only('index','info');
        $this->middleware('can:Editar Notas')->only('edit', 'update');
    }

    public function index(Request $request)
    {
        $estudiante = null;
        $cursos = null;
        $notas = null;

        $auth = Auth::user()->id;
        $user = User::findOrFail($auth);

        switch (true) {
            case $user->hasRole('Admin'):
            case $user->hasRole('Secretaria'):
            case $user->hasRole('Director'):

                $niveles = Nivel::all();
                $grados_primaria = Grado::where('id_nivel', 1)->get();
                $grados_secundaria = Grado::where('id_nivel', 2)->get();
                $query = Estudiante_Seccion::whereHas('estudiante.user', function ($query) use ($request) {
                    $query->where('esActivo', 1)->where('año_escolar', $request->año_escolar);
                });

                // if ($request->filled('año_escolar')){
                //     $query->where();
                // }
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

                $estudiantes = $query->paginate(10);

                return view('boleta_notas.index', compact('estudiantes', 'niveles', 'grados_primaria', 'grados_secundaria'));
                break;
            case $user->hasRole('Apoderado'):
                $apoderado = Apoderado::where('user_id', $user->id)->first();
                $estudiantes = Estudiante_Seccion::where('año_escolar', '2024')
                    ->whereHas('estudiante', function ($query) use ($apoderado) {
                    $query->where('id_apoderado', $apoderado->id)
                        ->whereHas('user', function ($query) {
                            $query->where('esActivo', 1);
                        });
                })->paginate(10);
                return view('boleta_notas.index', compact('estudiantes'));
                break;
            case $user->hasRole('Estudiante_Matriculado'):
                $estudiante = Estudiante_Seccion::where('user_id', $user->id)
                    ->where('año_escolar','2024')->first();
                $cursos = Curso_por_nivel::where('id_nivel', $estudiante->id_nivel)
                    ->whereNotIn('codigo_curso', function ($query) use ($estudiante) {
                        $query->select('codigo_curso')
                            ->from('exoneraciones')
                            ->where('codigo_estudiante', $estudiante->codigo_estudiante)
                            ->where('año_escolar', $estudiante->año_escolar);
                    })->get();
                $notas = Notas_por_competencia::where('codigo_estudiante', $estudiante->codigo_estudiante)
                    ->where('año_escolar', $estudiante->año_escolar)
                    ->where('exoneracion', 0)->get();
                return view('boleta_notas.index', compact('estudiante', 'cursos', 'notas'));
                break;
            default:
                break;
        }      
    }

    public function info(string $codigo_estudiante, string $año_escolar){
        $estudiante = Estudiante_Seccion::where('codigo_estudiante',$codigo_estudiante)
            ->where('año_escolar',$año_escolar)->first();
        $cursos = Curso_por_nivel::where('id_nivel', $estudiante->id_nivel)
            ->whereNotIn('codigo_curso', function ($query) use ($estudiante) {
                $query->select('codigo_curso')
                ->from('exoneraciones')
                ->where('codigo_estudiante', $estudiante->codigo_estudiante)
                    ->where('año_escolar', $estudiante->año_escolar);
            })->get();
        $notas = Notas_por_competencia::where('codigo_estudiante', $estudiante->codigo_estudiante)
            ->where('año_escolar', $estudiante->año_escolar)
            ->where('exoneracion', 0)->get();
        return view('boleta_notas.info', compact('estudiante', 'cursos', 'notas'));
    }

    public function edit(string $codigo_curso,string $bimestre,string $nivel,string $grado,string $seccion)
    {
        $curso = Curso::where('codigo_curso', $codigo_curso)
            ->where('esActivo', 1)
            ->first();
        $competencias = $curso->competencias;
        $aula = Seccion::where('id_nivel', $nivel)
            ->where('id_grado', $grado)
            ->where('id_seccion', $seccion)
            ->first();
        $bimestre_activo = Bimestre::where('esActivo',1)->first();
        $estudiantes = Estudiante_Seccion::where('año_escolar', Carbon::now()->year)
            ->where('id_nivel', $nivel)
            ->where('id_grado', $grado)
            ->where('id_seccion', $seccion)
            ->whereDoesntHave('exoneraciones', function ($query) use ($codigo_curso) {
                $query->where('codigo_curso', $codigo_curso);
            })
            ->get();
        $codigo_estudiantes = $estudiantes->pluck('codigo_estudiante')->toArray();
        $notas = Notas_por_competencia::where('codigo_curso', $codigo_curso)
            ->where('id_bimestre', $bimestre)
            ->where('año_escolar', Carbon::now()->year)
            ->whereIn('codigo_estudiante', $codigo_estudiantes)
            ->get();
        return view('boleta_notas.edit', compact('bimestre','estudiantes','notas','curso','aula','competencias'));
    }

    public function update(Request $request,string $codigo_curso, string $nivel, string $grado, string $seccion)
    {
        $curso = Curso::where('codigo_curso', $codigo_curso)
            ->where('esActivo', 1)
            ->first();
        $competencias = $curso->competencias;
        $aula = Seccion::where('id_nivel', $nivel)
            ->where('id_grado', $grado)
            ->where('id_seccion', $seccion)
            ->first();
        $notas = $request->input('notas');

        foreach ($notas as $codigo_estudiante => $competencias) {
            foreach ($competencias as $orden => $nota_data) {
                Notas_por_competencia::where([
                    'codigo_estudiante' => $nota_data['codigo_estudiante'],
                    'año_escolar' => $nota_data['año_escolar'],
                    'user_id' => $nota_data['user_id'],
                    'id_bimestre' => $nota_data['id_bimestre'],
                    'codigo_curso' => $nota_data['codigo_curso'],
                    'orden' => $nota_data['orden']
                ])->update([
                    'nivel_logro' => $nota_data['nivel_logro'],
                ]);
            }
        }

        return redirect()->route('estudiantes.filtrar-por-aula', ['codigo_curso' => $curso->codigo_curso, 'nivel' =>$aula->id_nivel , 'grado' =>$aula->id_grado , 'seccion' =>$aula->id_seccion ])->with('success', 'Notas registradas correctamente.');
    }

}
