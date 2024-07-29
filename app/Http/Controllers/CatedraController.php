<?php

namespace App\Http\Controllers;

use App\Models\Catedra;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\Seccion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatedraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $auth = Auth::user()->id;
        $user = User::findOrFail($auth);
        $filtra_nivel = $request->input('nivel');
        $filtra_grado = $request->input('grado');
        $filtra_seccion = $request->input('seccion');

        $cursos = Curso::where('esActivo', 1)
        ->whereHas('niveles', function($query) use ($filtra_nivel) {
            $query->where('curso_por_niveles.id_nivel', $filtra_nivel);
        })
        ->with(['catedras' => function($query) use ($filtra_nivel, $filtra_grado, $filtra_seccion) {
            $query->where('id_nivel', $filtra_nivel)
                  ->where('id_grado', $filtra_grado)
                  ->where('id_seccion', $filtra_seccion)
                  ->where('año_escolar',2024)
                  ->with('docente');
        }])
        ->paginate(10)
        ->appends([
            'nivel' => $filtra_nivel,
            'grado' => $filtra_grado,
            'seccion' => $filtra_seccion,
        ]);

        $niveles = Nivel::all();
        $grados_primaria = Grado::where('id_nivel', 1)->get();
        $grados_secundaria = Grado::where('id_nivel', 2)->get();

        if($filtra_nivel!=null) {
            $aula = Seccion::where('id_nivel',$filtra_nivel)
                           ->where('id_grado',$filtra_grado)
                           ->where('id_seccion',$filtra_seccion)
                           ->first();
            return view('catedras.index',compact('cursos', 'user', 'niveles', 'grados_primaria', 'grados_secundaria' , 'aula'));
        }
        else
        {
            return view('catedras.index',compact('cursos', 'user', 'niveles', 'grados_primaria', 'grados_secundaria' ,'filtra_nivel', 'filtra_grado', 'filtra_seccion'));
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
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
    public function edit(string $aula)
    {
        // $curso = Curso::findOrFail($codigo_curso);
        $docentes = Docente::all();
        dd($aula);
        // $nivel = $aula->id_nivel;
        // $grado = $aula->id_grado;
        // $seccion = $aula->id_seccion;
        $año = 2024;
        return view('catedras.create', compact('curso', 'año'));
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
}
