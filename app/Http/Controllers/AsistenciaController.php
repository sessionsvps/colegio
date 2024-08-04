<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Bimestre;
use App\Models\Estudiante;
use App\Models\Estudiante_Seccion;
use App\Models\Grado;
use App\Models\Nivel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends BaseController
{

    public function __construct()
    {
        $this->middleware('can:Ver Asistencias')->only('index');
        $this->middleware('can:Editar Asistencias')->only('edit','update');
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
            case $user->hasRole('Secretaria'):
            case $user->hasRole('Director'):
                $niveles = Nivel::all();
                $grados_primaria = Grado::where('id_nivel', 1)->get();
                $grados_secundaria = Grado::where('id_nivel', 2)->get();

                $año_escolar = $request->input('año_escolar');
                $query = Estudiante_Seccion::whereHas('estudiante.user', function ($query) {
                    $query->where('esActivo', 1);
                })->where('año_escolar',$año_escolar);
                
                if ($request->filled('nivel')) {
                    $query->where('id_nivel', $request->nivel);
                }
                if ($request->filled('grado')) {
                    $query->where('id_grado', $request->grado);
                }
                if ($request->filled('seccion')) {
                    $query->where('id_seccion', $request->seccion);
                }

                if ($request->filled('buscar_por')) {
                    $buscarPor = $request->input('buscar_por');
                    $buscarValor = $request->input($buscarPor);

                    if ($buscarPor === 'codigo') {
                        $query->whereHas('estudiante', function ($query) use ($buscarValor) {
                            $query->where('codigo_estudiante', $buscarValor);
                        });
                    } elseif ($buscarPor === 'nombre') {
                        $query->whereHas('estudiante', function ($query) use ($buscarValor) {
                            $query->where(function ($query) use ($buscarValor) {
                                $query->where(DB::raw("CONCAT(primer_nombre, ' ', otros_nombres, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscarValor . '%')
                                    ->orWhere(DB::raw("CONCAT(primer_nombre, ' ', apellido_paterno, ' ', apellido_materno)"), 'like', '%' . $buscarValor . '%');
                            });
                        });
                    } elseif ($buscarPor === 'dni') {
                        $query->whereHas('estudiante', function ($query) use ($buscarValor) {
                            $query->where('dni', $buscarValor);
                        });
                    } elseif ($buscarPor === 'correo') {
                        $query->whereHas('estudiante.user', function ($query) use ($buscarValor) {
                            $query->where('email', 'like', '%' . $buscarValor . '%');
                        });
                    }
                }

                $estudiantes = $query->get();
                $codigo_estudiantes = $estudiantes->pluck('codigo_estudiante');
                $asistencias = Asistencia::where('id_bimestre', $request->input('bimestre'))
                    ->where('año_escolar', $request->input('año_escolar'))
                    ->whereIn('codigo_estudiante', $codigo_estudiantes)
                    ->get();
                return view('asistencias.index', compact('bimestres','estudiantes','asistencias','niveles','grados_primaria','grados_secundaria'));
                break;
            case $user->hasRole('Estudiante_Matriculado'):
                $estudiante = Estudiante_Seccion::where('user_id', $user->id)
                    ->where('año_escolar', $request->año_escolar)->first();
                $asistencia = Asistencia::where('user_id', $user->id)
                    ->where('id_bimestre', $request->bimestre)
                    ->where('año_escolar', $request->año_escolar)->first();
                return view('asistencias.index', compact('estudiante', 'asistencia','bimestres'));
                break;
            default:
                break;
        }      
        
    }

    public function edit(string $codigo_estudiante, string $id_bimestre, string $año_escolar)
    {
        $estudiante = Estudiante_Seccion::where('codigo_estudiante',$codigo_estudiante)
            ->where('año_escolar',$año_escolar)->first();
        $asistencia = Asistencia::where('codigo_estudiante', $codigo_estudiante)
            ->where('año_escolar', $año_escolar)
            ->where('id_bimestre', $id_bimestre)->first();
        return view('asistencias.edit',compact('estudiante','asistencia'));
    }

    public function update(Request $request, string $codigo_estudiante, string $id_bimestre, string $año_escolar){
        // Validar los datos del formulario
        $request->validate([
            'inasistencias_justificadas' => 'required|integer|max:999',
            'inasistencias_injustificadas' => 'required|integer|max:999',
            'tardanzas_justificadas' => 'required|integer|max:999',
            'tardanzas_injustificadas' => 'required|integer|max:999',
        ]);

        // Buscar el registro de asistencia
        $asistencia = Asistencia::where('codigo_estudiante', $codigo_estudiante)
            ->where('año_escolar', $año_escolar)
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
