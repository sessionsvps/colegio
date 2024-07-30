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
                  ->where('año_escolar',Carbon::now()->year)
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
        // dd($filtra_nivel);

        if($filtra_nivel!=null) {
            $aula = Seccion::where('id_nivel',$filtra_nivel)
                           ->where('id_grado',$filtra_grado)
                           ->where('id_seccion',$filtra_seccion)
                           ->first();
            return view('catedras.index',compact('cursos', 'user', 'niveles', 'grados_primaria', 'grados_secundaria' , 'aula', 'filtra_nivel'));
        }
        else
        {
            return view('catedras.index',compact('cursos', 'user', 'niveles', 'grados_primaria', 'grados_secundaria'));
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
       
    // }
    
    public function create(string $codigo_curso, string $nivel, string $grado, string $seccion)
    {
        $curso = Curso::findOrFail($codigo_curso);
        $docentes = Docente::whereHas('user', function($query) {
            $query->where('esActivo', 1);
        })->get();
        $aula = Seccion::where('id_nivel',$nivel)
                           ->where('id_grado',$grado)
                           ->where('id_seccion',$seccion)
                           ->first();
        $año = Carbon::now()->year;
        return view('catedras.create', compact('curso', 'nivel', 'grado', 'seccion', 'año', 'aula', 'docentes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo_curso' => 'required',
            'codigo_docente' =>'required|exists:docentes,codigo_docente',
            'id_nivel' => 'required',
            'id_grado' => 'required',
            'id_seccion' => 'required',
            'año_escolar' =>'required'
        ], 
        [

        ]);

        $codigo_docente = $request->codigo_docente;
        $docente = Docente::where('codigo_docente', $codigo_docente)->firstOrFail();
        $user_id = $docente->user->id;
        // dd($codigo_docente, $user_id);

        Catedra::create([
            'codigo_curso' => $request->codigo_curso,
            'codigo_docente' => $request->codigo_docente,
            'id_nivel' => $request->id_nivel,
            'id_grado' => $request->id_grado,
            'id_seccion' => $request->id_seccion,
            'año_escolar' => $request->año_escolar,
            'user_id' => $user_id,
        ]);
        
        return redirect()->route('catedras.index', [
            'nivel' => $request->id_nivel,
            'grado' => $request->id_grado,
            'seccion' => $request->id_seccion,
        ])->with('success', 'Docente asignado exitosamente.');
    }

    public function cancelar($nivel, $grado, $seccion) {
        return redirect()->route('catedras.index', [
            'nivel' => $nivel,
            'grado' => $grado,
            'seccion' => $seccion,
        ]);
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
    public function edit(string $codigo_curso, string $nivel, string $grado, string $seccion)
    {
        $curso = Curso::findOrFail($codigo_curso);
        $docentes = Docente::whereHas('user', function($query) {
            $query->where('esActivo', 1);
        })->get();
        $aula = Seccion::where('id_nivel',$nivel)
                           ->where('id_grado',$grado)
                           ->where('id_seccion',$seccion)
                           ->first();
        $año = Carbon::now()->year;
        $catedra = Catedra::where('codigo_curso', $codigo_curso)
                          ->where('id_nivel', $nivel)
                          ->where('id_grado', $grado)
                          ->where('id_seccion', $seccion)
                          ->firstOrFail();
        return view('catedras.edit', compact('curso', 'nivel', 'grado', 'seccion', 'año', 'aula', 'catedra', 'docentes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $codigo_curso, $nivel, $grado, $seccion)
    {
        $request->validate([
            'codigo_docente' => 'required|exists:docentes,codigo_docente',
        ]);

        $catedra = Catedra::where([
            ['codigo_curso', '=', $codigo_curso],
            ['id_nivel', '=', $nivel],
            ['id_grado', '=', $grado],
            ['id_seccion', '=', $seccion],
            ['año_escolar', '=', Carbon::now()->year],
        ])->firstOrFail();

        $codigo_docente = $request->codigo_docente;
        $docente = Docente::where('codigo_docente', $codigo_docente)->firstOrFail();
        $user_id = $docente->user->id;
        
        $aux = $catedra;

        $catedra->codigo_docente = $codigo_docente;
        $catedra->user_id = $user_id;
        // dd($aux, $catedra);
        $catedra->save();

        return redirect()->route('catedras.index', [
            'nivel' => $nivel,
            'grado' => $grado,
            'seccion' => $seccion,
        ])->with('success', 'Docente modificado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nivel, $grado, $seccion)
    {
        // Catedra en cuestion
        $catedra = Catedra::where('id_nivel', $nivel)
                          ->where('id_grado', $grado)
                          ->where('id_seccion', $seccion)
                          ->firstOrFail();
        // Estado 0                  
        $catedra->esActivo = 0;
        $catedra->save();
        // Redirigir a index con un request
        return redirect()->route('catedras.index', [
            'nivel' => $nivel,
            'grado' => $grado,
            'seccion' => $seccion,     
        ])->with('sucess', 'Docente asignado removido.');
    }
}
