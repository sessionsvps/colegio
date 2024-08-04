<?php

namespace App\Http\Controllers;

use App\Models\Catedra;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Estudiante_Seccion;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\Seccion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Routing\Controller as BaseController;

class CursoController extends BaseController
{

    public function __construct()
    {
        $this->middleware('can:Ver Cursos')->only('index','info');
        $this->middleware('can:Registrar Cursos')->only('create', 'store');
        $this->middleware('can:Editar Cursos')->only('edit', 'update');
        $this->middleware('can:Eliminar Cursos')->only('destroy');
    }

    public function index(Request $request)
    {
        $auth = Auth::user()->id;
        $user = User::findOrFail($auth);
        $niveles = Nivel::all();
        switch(true) {
            case $user->hasRole('Admin'):
            case $user->hasRole('Secretaria'):
            case $user->hasRole('Director'):
                $filtranivel = $request->input('nivel_educativo');
                if ($filtranivel == null || $filtranivel == 0) {
                    $cursos = Curso::where('esActivo','=',1)->get();
                } else {
                    $cursos = Curso::where('esActivo','=', 1)->whereHas('niveles', function($query) use ($filtranivel) {
                        $query->where('curso_por_niveles.id_nivel','=',$filtranivel);
                    })->paginate(20)->appends(['nivel_educativo' => $filtranivel]);
                }
                return view('cursos.index',compact('cursos', 'niveles', 'filtranivel', 'user'));   
            break;
            case $user->hasRole('Estudiante_Matriculado'):
                $estudiante = Estudiante_Seccion::where('user_id',$user->id)->first();
                $cursos = DB::table('cursos')
                ->join('curso_por_niveles', 'cursos.codigo_curso', '=', 'curso_por_niveles.codigo_curso')
                ->leftJoin('exoneraciones', function ($join) use ($estudiante) {
                    $join->on('cursos.codigo_curso', '=', 'exoneraciones.codigo_curso')
                    ->where('exoneraciones.codigo_estudiante', $estudiante->codigo_estudiante)
                    ->where('exoneraciones.año_escolar', $estudiante->año_escolar);
                })
                ->where('cursos.esActivo', 1)
                ->where('curso_por_niveles.id_nivel', $estudiante->id_nivel)
                ->whereNull('exoneraciones.codigo_curso') // Filtrar los cursos que no están en la tabla exoneraciones
                ->select('cursos.*')
                ->get();

                return view('cursos.index',compact('cursos','user'));   
            break;
            case $user->hasRole('Docente'):
                $user_id = $user->id;
                $docente = Docente::whereHas('user', function($query) use($user_id) {
                    $query->where('id',$user_id);
                })->firstOrFail();

                // Coleccion de catedras del docente del año actual
                $catedras = Catedra::where('año_escolar', Carbon::now()->year)
                    ->where('codigo_docente', $docente->codigo_docente)->get();
                $cursos = new Collection();
                $codigo_cursos = [];
                foreach($catedras as $catedra){
                    $q_curso = Curso::where('codigo_curso', $catedra->codigo_curso)
                        ->where('esActivo', 1)
                        ->firstOrFail();
                    if($q_curso && !in_array($q_curso->codigo_curso, $codigo_cursos)) {
                        $codigo_cursos[] = $q_curso->codigo_curso;
                        $cursos->push($q_curso);    
                    }
                }
                return view('cursos.index', compact('cursos','user','catedras'));
            break;
            default:
            break;
        }      
    }

    public function mallaCurricular()
    {
        $cursos = Curso::where('esActivo',1)->get();
        return view('cursos.malla',compact('cursos'));
    }

    public function info(Request $request, string $codigo_curso, string $año_escolar)
    {
        $auth = Auth::user()->id;
        $user = User::findOrFail($auth);
        $user_id = $user->id;
        $curso = Curso::where('codigo_curso', $codigo_curso)->firstOrFail();
        $competencias = $curso->competencias;
        switch (true) {
            case $user->hasRole('Admin'):
            case $user->hasRole('Director'):
            case $user->hasRole('Secretaria'):
                $catedras = Catedra::where('codigo_curso', $codigo_curso)
                ->where('año_escolar', $año_escolar)->get();
                $docentes = Docente::whereIn('codigo_docente', $catedras->pluck('codigo_docente'))->get();
                return view('cursos.info', compact('curso', 'competencias','docentes'));
                break;
            case $user->hasRole('Estudiante_Matriculado'):
                $estudiante = Estudiante_Seccion::where('user_id',$user_id)
                    ->where('año_escolar',$año_escolar)->first();
                $catedras = Catedra::where('codigo_curso', $codigo_curso)
                    ->where('id_nivel',$estudiante->id_nivel)
                    ->where('id_grado', $estudiante->id_grado)
                    ->where('id_seccion', $estudiante->id_seccion)
                    ->where('año_escolar', $año_escolar)->get();
                $docentes = Docente::whereIn('codigo_docente', $catedras->pluck('codigo_docente'))->get();
                return view('cursos.info', compact('curso', 'competencias','docentes'));
                break;
            case $user->hasRole('Docente'):
                $docente = Docente::whereHas('user', function ($query) use ($user_id) {
                    $query->where('id', $user_id)
                    ->where('esActivo', 1);
                })->firstOrFail();
                $catedras = Catedra::where('codigo_docente', $docente->codigo_docente)
                ->where('codigo_curso', $curso->codigo_curso)
                ->where('año_escolar',$año_escolar)->get();
                $aulas = new Collection();
                foreach ($catedras as $catedra) {
                    $aula = Seccion::where('id_nivel', $catedra->id_nivel)
                        ->where('id_grado', $catedra->id_grado)
                        ->where('id_seccion', $catedra->id_seccion)
                        ->first();
                    $aulas->push($aula);
                }
                return view('cursos.info', compact('aulas', 'curso','competencias'));
                break;
            default:
            break;
        }      
        
    }
}
