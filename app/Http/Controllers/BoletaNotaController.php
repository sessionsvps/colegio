<?php

namespace App\Http\Controllers;

use App\Models\Bimestre;
use App\Models\Curso;
use App\Models\Curso_por_nivel;
use App\Models\Estudiante_Seccion;
use App\Models\Notas_por_competencia;
use App\Models\Seccion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class BoletaNotaController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('can:Ver Notas')->only('index');
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
                if ($request->filled('codigo_estudiante') && $request->filled('año_escolar')) {
                    $estudiante = Estudiante_Seccion::where('codigo_estudiante', $request->input('codigo_estudiante'))
                    ->where('año_escolar', $request->input('año_escolar'))->first();
                    if ($estudiante) {
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
                    }
                }
                return view('boleta_notas.index', compact('estudiante', 'cursos', 'notas'));
                break;
            case $user->hasRole('Estudiante_Matriculado'):
                $estudiante = Estudiante_Seccion::where('user_id', $user->id)->first();
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

    public function edit(string $codigo_curso,string $nivel,string $grado,string $seccion)
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
            ->where('id_bimestre', $bimestre_activo->id)
            ->where('año_escolar', Carbon::now()->year)
            ->whereIn('codigo_estudiante', $codigo_estudiantes)
            ->get();
        return view('boleta_notas.edit', compact('estudiantes','notas','curso','aula','competencias'));
    }

    public function update(Request $request, string $codigo_curso, string $nivel, string $grado, string $seccion)
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
                    'exoneracion' => false // Ajusta según tu lógica de negocio
                ]);
            }
        }

        return redirect()->route('estudiantes.filtrar-por-aula', ['codigo_curso' => $curso->codigo_curso, 'nivel' =>$aula->id_nivel , 'grado' =>$aula->id_grado , 'seccion' =>$aula->id_seccion ])->with('success', 'Notas registradas correctamente.');
    }

}
