<?php

namespace App\Http\Controllers;

use App\Models\Curso_por_nivel;
use App\Models\Estudiante_Seccion;
use App\Models\Exoneracion;
use Illuminate\Http\Request;

class ExoneracionController extends Controller
{

    public function index(Request $request)
    {
        $estudiante = null;
        $exoneraciones = collect();

        if ($request->filled('codigo_estudiante') && $request->filled('año_escolar')) {
            $estudiante = Estudiante_Seccion::where('codigo_estudiante', $request->input('codigo_estudiante'))
                ->where('año_escolar', $request->input('año_escolar'))->first();
            if ($estudiante) {
                $cursos = Curso_por_nivel::where('id_nivel', $estudiante->id_nivel)->get();
                $codigoCursos = $cursos->pluck('codigo_curso');

                $exoneraciones = Exoneracion::where('codigo_estudiante', $estudiante->codigo_estudiante)
                    ->where('año_escolar', $estudiante->año_escolar)
                    ->whereIn('codigo_curso', $codigoCursos)
                    ->get();
            }
        }

        return view('exoneraciones.index', compact('estudiante','exoneraciones'));
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

}
