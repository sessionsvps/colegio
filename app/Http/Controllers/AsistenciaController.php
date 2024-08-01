<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Bimestre;
use App\Models\Estudiante_Seccion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class AsistenciaController extends BaseController
{

    public function __construct()
    {
        $this->middleware('can:asistencias.index')->only('index');
        $this->middleware('can:asistencias.create')->only('edit','update');
    }

    public function index(Request $request)
    {   
        $bimestres = Bimestre::all();
        $estudiante = null;
        $asistencia = null;

        $auth = Auth::user()->id;
        $user = User::findOrFail($auth);

        switch (true) {
            case $user->hasRole('Admin'):
                if ($request->filled('codigo_estudiante') && $request->filled('bimestre')) {
                    $estudiante = Estudiante_Seccion::where('codigo_estudiante', $request->input('codigo_estudiante'))->first();
                    $asistencia = Asistencia::where('codigo_estudiante', $request->input('codigo_estudiante'))
                    ->where('id_bimestre', $request->input('bimestre'))->first();
                }
                return view('asistencias.index', compact('bimestres', 'estudiante', 'asistencia'));
                break;
            default:
                $bimestre_activo = Bimestre::where('esActivo',1)->first();
                $estudiante = Estudiante_Seccion::where('user_id',$user->id)->first();
                $asistencia = Asistencia::where('codigo_estudiante', $estudiante->codigo_estudiante)
                    ->where('id_bimestre', $bimestre_activo->id)->first();
                return view('asistencias.index', compact('estudiante', 'asistencia'));
                break;
        }      
        
    }

    public function edit(string $codigo_estudiante, string $id_bimestre)
    {
        $estudiante = Estudiante_Seccion::where('codigo_estudiante',$codigo_estudiante)->first();
        $asistencia = Asistencia::where('codigo_estudiante', $codigo_estudiante)
            ->where('id_bimestre', $id_bimestre)->first();
        return view('asistencias.edit',compact('estudiante','asistencia'));
    }

    public function update(Request $request, string $codigo_estudiante, string $id_bimestre){
        // Validar los datos del formulario
        $request->validate([
            'inasistencias_justificadas' => 'required|integer|max:999',
            'inasistencias_injustificadas' => 'required|integer|max:999',
            'tardanzas_justificadas' => 'required|integer|max:999',
            'tardanzas_injustificadas' => 'required|integer|max:999',
        ]);

        // Buscar el registro de asistencia
        $asistencia = Asistencia::where('codigo_estudiante', $codigo_estudiante)
            ->where('id_bimestre', $id_bimestre)->first();

        // Actualizar los datos de asistencia
        $asistencia->inasistencias_justificadas = $request->input('inasistencias_justificadas');
        $asistencia->inasistencias_injustificadas = $request->input('inasistencias_injustificadas');
        $asistencia->tardanzas_justificadas = $request->input('tardanzas_justificadas');
        $asistencia->tardanzas_injustificadas = $request->input('tardanzas_injustificadas');
        $asistencia->save();

        // Redirigir con un mensaje de éxito
        return redirect()->route('asistencias.index')->with('success', 'Asistencia actualizada correctamente');
    }
}
