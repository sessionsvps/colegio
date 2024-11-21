<?php

namespace App\Http\Controllers;

use App\Models\Bimestre;
use App\Models\Catedra;
use App\Models\Curso_por_nivel;
use App\Models\Estudiante_Seccion;
use App\Models\Exoneracion;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el ID del estudiante autenticado
        $userId = Auth::user()->id;
        $user = User::findOrFail($userId);

        //$codigo_estudiante = $estudiante->codigo_estudiante;
        $niveles = Nivel::all();
        $grados_primaria = Grado::where('id_nivel', 1)->get();
        $grados_secundaria = Grado::where('id_nivel', 2)->get();
        $bimestres = Bimestre::all();

        // Obtener cursos solo por nivel
        $cursos = Curso_por_nivel::with('curso', 'nivel')->get();
        $cursosPorNivel = [];
        foreach ($cursos as $curso) {
            $cursosPorNivel[$curso->nivel->id_nivel][] = [
                'id_curso' => $curso->codigo_curso,
                'nombre_curso' => $curso->curso->descripcion
            ];
        }

        if ($user->hasRole('Estudiante_Matriculado')) {
            $cantidadCursos = 0;
            $cantidadExoneraciones = 0;
            $aula = '';
            $cantidadCatedras = 0;
            $cantidadAulas = 0;

            // Obtener el código del estudiante
            $estudiante = Estudiante_Seccion::where('user_id', $userId)
                ->where('año_escolar','2024')->first();
            // Contar la cantidad de cursos que tiene el estudiante
            $cantidadCursos = Curso_por_nivel::where('id_nivel', $estudiante->id_nivel)
            ->whereNotIn('codigo_curso', function ($query) use ($estudiante) {
                $query->select('codigo_curso')
                ->from('exoneraciones')
                ->where('codigo_estudiante', $estudiante->codigo_estudiante)
                    ->where('año_escolar', '2024');
            })->count();
            // Contar la cantidad de exoneraciones que tiene el estudiante
            $cantidadExoneraciones = Exoneracion::where('codigo_estudiante', $estudiante->codigo_estudiante)
                ->where('año_escolar','2024')->count();
            $aula = $estudiante->seccion;

            return view('dashboard', compact('cantidadCursos', 'cantidadExoneraciones', 'aula'));
        }else if($user->hasRole('Docente')){
            $cantidadCatedras = Catedra::where('user_id', $userId)->count();
            // Contar la cantidad de aulas (secciones) a las que dicta el docente
            $cantidadAulas = Catedra::where('user_id', $userId)->distinct('id_seccion')->count('id_seccion');
            return view('dashboard', compact('cantidadCatedras', 'cantidadAulas', 'niveles', 'grados_primaria', 'grados_secundaria', 'bimestres', 'cursosPorNivel'));
        }else{
            return view('dashboard', compact('niveles', 'grados_primaria', 'grados_secundaria', 'bimestres', 'cursosPorNivel'));
        }

    }
}
