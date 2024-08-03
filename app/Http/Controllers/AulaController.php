<?php

namespace App\Http\Controllers;

use App\Models\Estudiante_Seccion;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\Seccion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class AulaController extends BaseController
{

    public function __construct()
    {
        $this->middleware('can:Ver Aulas')->only('index');
    }

    public function index(Request $request)
    {
        $auth = Auth::user()->id;
        $user = User::findOrFail($auth);
        switch (true) {
            case $user->hasRole('Admin'):
            case $user->hasRole('Secretaria'):
            case $user->hasRole('Director'):
                $query = Seccion::query();
                $niveles = Nivel::all();
                $grados_primaria = Grado::where('id_nivel',1)->get();
                $grados_secundaria = Grado::where('id_nivel', 2)->get();
                if ($request->filled('nivel')) {
                    $query->where('id_nivel', $request->nivel);
                }
                if ($request->filled('grado')) {
                    $query->where('id_grado', $request->grado);
                }
                if ($request->filled('seccion')) {
                    $query->where('id_seccion', $request->seccion);
                }
                $aulas = $query->paginate(10);
                return view('aulas.index', compact('aulas','niveles','grados_primaria','grados_secundaria'));  
                break;
            case $user->hasRole('Estudiante_Matriculado'):
                $estudiante = Estudiante_Seccion::where('user_id',$user->id)->first();
                $aula = $estudiante->seccion;
                return view('aulas.index', compact('aula'));  
                break;
            default:
                break;
        }      
    }

    // public function info(string $codigo_curso, Request $request)
    // {
    //     $auth = Auth::user()->id;
    //     $user = User::findOrFail($auth);
    //     $user_id = $user->id;
    //     switch (true) {
    //         case $user->hasRole('Admin'):
    //         case $user->hasRole('Director'):
    //         case $user->hasRole('Secretaria'):
    //             $query = Catedra::where('codigo_curso', $codigo_curso);

    //             // Obtener niveles, grados y secciones relacionados con las cátedras del curso
    //             $niveles = Nivel::all();
    //             $grados_primaria = Grado::where('id_nivel', 1)->get();
    //             $grados_secundaria = Grado::where('id_nivel', 2)->get();

    //             // Filtrar cátedras según los parámetros del request
    //             if ($request->filled('nivel')) {
    //                 $query->where('id_nivel', $request->nivel);
    //             }

    //             if ($request->filled('grado')) {
    //                 $query->where('id_grado', $request->grado);
    //             }

    //             if ($request->filled('seccion')) {
    //                 $query->where('id_seccion', $request->seccion);
    //             }

    //             if ($request->filled('docente')) {
    //                 $query->where('codigo_docente', $request->docente);
    //             }

    //             $catedras_filtradas = $query->get();

    //             return view('cursos.info', compact('curso', 'competencias', 'catedras_filtradas', 'docentes', 'niveles', 'grados_primaria', 'grados_secundaria'));
    //             break;
    //         case $user->hasRole('Estudiante_Matriculado'):
    //             return view('cursos.info', compact('curso', 'competencias', 'docentes'));
    //             break;
    //         case $user->hasRole('Docente'):
    //             $docente = Docente::whereHas('user', function ($query) use ($user_id) {
    //                 $query->where('id', $user_id)
    //                     ->where('esActivo', 1);
    //             })->firstOrFail();
    //             $catedras = Catedra::where('codigo_docente', $docente->codigo_docente)
    //                 ->where('codigo_curso', $curso->codigo_curso)
    //                 ->get();
    //             $aulas = new Collection();
    //             foreach ($catedras as $catedra) {
    //                 $aula = Seccion::where('id_nivel', $catedra->id_nivel)
    //                     ->where('id_grado', $catedra->id_grado)
    //                     ->where('id_seccion', $catedra->id_seccion)
    //                     ->first();
    //                 $aulas->push($aula);
    //             }
    //             return view('cursos.info', compact('aulas', 'curso', 'competencias'));
    //             break;
    //     }
    // }

}
