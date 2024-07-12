<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Nivel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission; 

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(?Request $request)
    {
        $auth = Auth::user()->id;
        $user = User::findOrFail($auth);
        $niveles = Nivel::all();
        switch(true) {
            case $user->hasRole('Admin'):
                $filtranivel = $request->input('nivel_educativo');
                
        
                if ($filtranivel == null || $filtranivel == 0) {
                    $cursos = Curso::where('esActivo','=',1)->paginate(10);
                } else {
                    $cursos = Curso::where('esActivo','=', 1)->whereHas('niveles', function($query) use ($filtranivel) {
                        $query->where('curso_por_niveles.id_nivel','=',$filtranivel);
                    })->paginate(10)->appends(['nivel_educativo' => $filtranivel]);
                }
                return view('cursos.index',compact('cursos', 'niveles', 'filtranivel', 'user'));   
            break;
            case $user->hasRole('Estudiante'):
                $estudiante = Estudiante::where('user_id',$user->id)->first();
                $filtranivel = $estudiante->nivel_id;
                $cursos = Curso::where('esActivo','=', 1)->whereHas('niveles', function($query) use ($filtranivel) {
                    $query->where('curso_por_niveles.id_nivel','=',$filtranivel);
                })->paginate(10)->appends(['nivel_educativo' => $filtranivel]);
                return view('cursos.index',compact('cursos', 'niveles', 'filtranivel', 'user'));   
            break;
            case $user->hasRole('Docente'):

                //Mostrar cursos que dicta el docente

            break;
            default:
            break;
        }      
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function edit(string $id)
    {
        //
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
