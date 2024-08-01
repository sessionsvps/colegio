<?php

namespace App\Http\Controllers;

use App\Models\Bimestre;
use App\Models\Curso;
use App\Models\Curso_por_nivel;
use App\Models\Estudiante;
use App\Models\Estudiante_Seccion;
use App\Models\Notas_por_competencia;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class BoletaNotaController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('can:notas.index')->only('index');
        $this->middleware('can:notas.admin')->only('index');
        $this->middleware('can:notas.create')->only('edit', 'update');
    }

    public function index(Request $request)
    {
        $estudiante = null;
        $cursos = null;
        $notas = null;
        if ($request->filled('codigo_estudiante') && $request->filled('año_escolar')) {
            $estudiante = Estudiante_Seccion::where('codigo_estudiante', $request->input('codigo_estudiante'))
            ->where('año_escolar', $request->input('año_escolar'))->first();
            if($estudiante){
                $cursos = Curso_por_nivel::where('id_nivel', $estudiante->id_nivel)
                ->whereNotIn('codigo_curso', function ($query) use ($estudiante) {
                    $query->select('codigo_curso')
                    ->from('exoneraciones')
                    ->where('codigo_estudiante', $estudiante->codigo_estudiante)
                    ->where('año_escolar', $estudiante->año_escolar);
                })
                ->get();
                $notas = Notas_por_competencia::where('codigo_estudiante',$estudiante->codigo_estudiante)
                    ->where('año_escolar',$estudiante->año_escolar)
                    ->where('exoneracion', 0)->get();
            }
        }

        return view('boleta_notas.index', compact('estudiante', 'cursos','notas'));
    }

    public function edit(string $codigo_estudiante, string $codigo_curso, string $año_escolar)
    {
        $estudiante = Estudiante_Seccion::where('codigo_estudiante',$codigo_estudiante)->first();
        $curso = Curso::where('codigo_curso',$codigo_curso)->first();
        $notas = Notas_por_competencia::where('codigo_estudiante',$codigo_estudiante)
            ->where('codigo_curso',$codigo_curso)->where('año_escolar',$año_escolar)->get();
        $bimestres = Bimestre::all();
        $competencias = $curso->competencias;
        return view('boleta_notas.edit', compact('estudiante', 'curso', 'notas','bimestres','competencias'));
    }

    public function update(Request $request, string $codigo_estudiante, string $codigo_curso, string $año_escolar)
    {
        $estudiante = Estudiante_Seccion::where('codigo_estudiante',$codigo_estudiante)->first();
        $notas = $request->input('notas', []);

        foreach ($notas as $orden => $bimestres) {
            foreach ($bimestres as $bimestre_id => $nivel_logro) {
                if ($nivel_logro === '') {
                    Notas_por_competencia::where('codigo_estudiante', $codigo_estudiante)
                    ->where('codigo_curso', $codigo_curso)
                    ->where('año_escolar', $año_escolar)
                    ->where('orden', $orden)
                    ->where('id_bimestre', $bimestre_id)
                    ->update(['nivel_logro' => null]);
                } else {
                    Notas_por_competencia::where('codigo_estudiante', $codigo_estudiante)
                    ->where('codigo_curso', $codigo_curso)
                    ->where('año_escolar', $año_escolar)
                    ->where('orden', $orden)
                    ->where('id_bimestre', $bimestre_id)
                    ->update(['nivel_logro' => $nivel_logro]);
                }
            }
        }

        return redirect()->route('estudiantes.filtrar-por-aula', ['codigo_curso' => $codigo_curso, 'nivel' => $estudiante->id_nivel, 'grado' => $estudiante->id_grado, 'seccion' => $estudiante->id_seccion]);
    }

}
