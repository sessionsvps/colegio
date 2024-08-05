<?php

namespace App\Http\Controllers;

use App\Models\Catedra;
use App\Models\Estudiante;
use App\Models\Estudiante_Seccion;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\Seccion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class AulaController extends BaseController
{

    public function __construct()
    {
        $this->middleware('can:Ver Aulas')->only('index');
    }

    public function index(Request $request)
    {
        $auth = Auth::user()->id;
        $user = User::findOrFail($auth);
        switch (true) {
            case $user->hasRole('Admin'):
            case $user->hasRole('Secretaria'):
            case $user->hasRole('Director'):
                $query = Seccion::query();
                $niveles = Nivel::all();
                $grados_primaria = Grado::where('id_nivel',1)->get();
                $grados_secundaria = Grado::where('id_nivel', 2)->get();
                if ($request->filled('nivel')) {
                    $query->where('id_nivel', $request->nivel);
                }
                if ($request->filled('grado')) {
                    $query->where('id_grado', $request->grado);
                }
                if ($request->filled('seccion')) {
                    $query->where('id_seccion', $request->seccion);
                }
                $aulas = $query->paginate(10);
                return view('aulas.index', compact('aulas','niveles','grados_primaria','grados_secundaria'));  
                break;
            case $user->hasRole('Estudiante_Matriculado'):
                $estudiante = Estudiante_Seccion::where('user_id',$user->id)->first();
                $aula = $estudiante->seccion;
                return view('aulas.index', compact('aula'));  
                break;
            default:
                break;
        }      
    }

    public function info(Request $request, string $año_escolar, string $nivel, string $grado, string $seccion)
    {
        $auth = Auth::user()->id;
        $user = User::findOrFail($auth);
        $user_id = $user->id;
        $aula = Seccion::where('id_nivel',$nivel)
            ->where('id_grado',$grado)
            ->where('id_seccion',$seccion)->first();
        $estudiantes = Estudiante_Seccion::where('año_escolar', $año_escolar)
            ->where('id_nivel', $nivel)
            ->where('id_grado', $grado)
            ->where('id_seccion', $seccion)->get();
        return view('aulas.info', compact('aula', 'estudiantes', 'año_escolar'));
    }

}
