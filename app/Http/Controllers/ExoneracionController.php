<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Curso_por_nivel;
use App\Models\Estudiante_Seccion;
use App\Models\Exoneracion;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;


class ExoneracionController extends BaseController
{

    public function __construct()
    {
        $this->middleware('can:exoneraciones.index')->only('index');
        $this->middleware('can:exoneraciones.create')->only('edit', 'update');
    }
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
            }
        }

        // Eliminar exoneraciones desmarcadas
        foreach ($exoneraciones_existentes as $exoneracion) {
            if (!in_array($exoneracion->codigo_curso, $cursos_exonerados)) {
                $exoneracion->delete();
            }
        }

        return redirect()->route('exoneraciones.index')->with('success', 'Exoneraciones actualizadas correctamente.');
    }

}
