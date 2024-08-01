<?php

namespace App\Http\Controllers;

use App\Models\Curso_por_nivel;
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
                $cursos = Curso_por_nivel::where('id_nivel', $estudiante->id_nivel)->get();
                $notas = Notas_por_competencia::where('codigo_estudiante',$estudiante->codigo_estudiante)
                    ->where('año_escolar',$estudiante->año_escolar)->get();
            }
        }

        return view('boleta_notas.index', compact('estudiante', 'cursos','notas'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
