<?php

namespace App\Http\Controllers;

use App\Models\Catedra;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Estudiante;
use App\Models\Estudiante_Seccion;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(?Request $request)
    {
        $auth = Auth::user()->id;
        $user = User::findOrFail($auth);
        $niveles = Nivel::all();
        switch(true) {
            case $user->hasRole('Admin'):
                $filtranivel = $request->input('nivel_educativo');
                if ($filtranivel == null || $filtranivel == 0) {
                    $cursos = Curso::where('esActivo','=',1)->paginate(10);
                } else {
                    $cursos = Curso::where('esActivo','=', 1)->whereHas('niveles', function($query) use ($filtranivel) {
                        $query->where('curso_por_niveles.id_nivel','=',$filtranivel);
                    })->paginate(10)->appends(['nivel_educativo' => $filtranivel]);
                }
                return view('cursos.index',compact('cursos', 'niveles', 'filtranivel', 'user'));   
            break;
            case $user->hasRole('Estudiante'):
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

                //Mostrar cursos que dicta el docente

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function info(string $codigo_curso, Request $request)
    {
        $auth = Auth::user()->id;
        $user = User::findOrFail($auth);
        $curso = Curso::where('codigo_curso', $codigo_curso)->firstOrFail();
        $competencias = $curso->competencias;
        $catedras = Catedra::where('codigo_curso', $codigo_curso)->get();
        $docentes = Docente::whereIn('codigo_docente', $catedras->pluck('codigo_docente'))->get();
        switch (true) {
            case $user->hasRole('Admin'):
                $query = Catedra::where('codigo_curso', $codigo_curso);

                // Obtener niveles, grados y secciones relacionados con las cátedras del curso
                $niveles = Nivel::all();
                $grados_primaria = Grado::where('id_nivel', 1)->get();
                $grados_secundaria = Grado::where('id_nivel', 2)->get();

                // Filtrar cátedras según los parámetros del request
                if ($request->filled('nivel')) {
                    $query->where('id_nivel', $request->nivel);
                }

                if ($request->filled('grado')) {
                    $query->where('id_grado', $request->grado);
                }

                if ($request->filled('seccion')) {
                    $query->where('id_seccion', $request->seccion);
                }

                if ($request->filled('docente')) {
                    $query->where('codigo_docente', $request->docente);
                }

                $catedras_filtradas = $query->get();

                return view('cursos.info', compact('curso', 'competencias', 'catedras_filtradas', 'docentes', 'niveles', 'grados_primaria', 'grados_secundaria'));
                break;
            case $user->hasRole('Estudiante'):
                return view('cursos.info', compact('curso', 'competencias','docentes'));
                break;
            case $user->hasRole('Docente'):

                //Mostrar cursos que dicta el docente

                break;
            default:
                break;
        }      
        
    }
}
