<?php

namespace App\Http\Controllers;

use App\Models\Catedra;
use App\Models\Curso_por_nivel;
use App\Models\Estudiante_Seccion;
use App\Models\Exoneracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener el ID del estudiante autenticado
        $userId = Auth::user()->id;

        $cantidadCursos = 0;
        $cantidadExoneraciones = 0;
        $aula = '';
        $cantidadCatedras = 0;
        $cantidadAulas = 0;

        // Obtener el código del estudiante
        $estudiante = Estudiante_Seccion::where('user_id', $userId)->first();

        //$codigo_estudiante = $estudiante->codigo_estudiante;

        if ($estudiante) {
            // Contar la cantidad de cursos que tiene el estudiante
            $cantidadCursos = Curso_por_nivel::where('id_nivel', $estudiante->id_nivel)->count();

            // Contar la cantidad de exoneraciones que tiene el estudiante
            $cantidadExoneraciones = Exoneracion::where('codigo_estudiante', $estudiante->codigo_estudiante)->count();

            $aula = $estudiante->seccion;
        }

        $cantidadCatedras = Catedra::where('user_id', $userId)->count();

        // Contar la cantidad de aulas (secciones) a las que dicta el docente
        $cantidadAulas = Catedra::where('user_id', $userId)->distinct('id_seccion')->count('id_seccion');

        return view('dashboard', compact('cantidadCursos', 'cantidadExoneraciones', 'aula', 'cantidadCatedras', 'cantidadAulas'));
    }
}
