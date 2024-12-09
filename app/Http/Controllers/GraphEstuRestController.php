<?php

namespace App\Http\Controllers;

use App\Models\Estudiante_Seccion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GraphEstuRestController extends Controller
{
    public function index(Request $request)
    {
        //$estudiante = Estudiante_Seccion::where('user_id', Auth::user()->id)->first();

        // Obtener el código del estudiante
        //$estudiante = Estudiante_Seccion::where('user_id', $user_id)->first();

        //$codigo_estudiante = $estudiante->codigo_estudiante;
        $nivel = $request->input('nivel');
        $grado = $request->input('grado');
        $bimestre = $request->input('bimestre');
        $curso = $request->input('curso');

        $nivel2 = $request->input('nivel2');
        $grado2 = $request->input('grado2');
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');

        $asis = DB::table('bimestres')
        ->join('asistencias', 'bimestres.id', '=', 'asistencias.id_bimestre')
        ->select(
            'bimestres.descripcion as bimestre',
            DB::raw('SUM(asistencias.inasistencias_justificadas) as inasistencias_justificadas'),
            DB::raw('SUM(asistencias.inasistencias_injustificadas) as inasistencias_injustificadas'),
            DB::raw('SUM(asistencias.tardanzas_justificadas) as tardanzas_justificadas'),
            DB::raw('SUM(asistencias.tardanzas_injustificadas) as tardanzas_injustificadas')
        )
            //->where('asistencias.codigo_estudiante', "0322")
            ->groupBy('bimestres.descripcion')
            ->get()
            ->map(function ($item) {
                $item->inasistencias_justificadas = (int) $item->inasistencias_justificadas;
                $item->inasistencias_injustificadas = (int) $item->inasistencias_injustificadas;
                $item->tardanzas_justificadas = (int) $item->tardanzas_justificadas;
                $item->tardanzas_injustificadas = (int) $item->tardanzas_injustificadas;
                return $item;
            });

        $logrosPorGradoSeccion = DB::table('notas_por_competencias')
        ->join('estudiante_secciones', 'notas_por_competencias.codigo_estudiante', '=', 'estudiante_secciones.codigo_estudiante')
        ->join('grados', 'estudiante_secciones.id_grado', '=', 'grados.id_grado')
        ->join('secciones', 'estudiante_secciones.id_seccion', '=', 'secciones.id_seccion')
        ->select(
            DB::raw("CONCAT(grados.detalle, ' ', secciones.detalle) as grado_seccion"),
            'notas_por_competencias.nivel_logro',
            DB::raw('COUNT(*) as total') // Cuenta todas las instancias, incluyendo repeticiones
        )
            ->where('grados.id_nivel', $nivel) // Filtrar solo para secundaria
            ->where('grados.id_grado', $grado) // Filtrar para 1er grado de secundaria
            ->where('notas_por_competencias.codigo_curso', $curso) // Filtrar para el curso "Ciencias Sociales"
            ->where('notas_por_competencias.id_bimestre', $bimestre) // Filtrar para el I Bimestre
            ->groupBy('grado_seccion', 'notas_por_competencias.nivel_logro')
            ->get()
            ->groupBy('grado_seccion')
            ->map(function ($items, $grado_seccion) {
                $result = [
                    'grado_seccion' => $grado_seccion,
                    'nivel_logro_A' => 0,
                    'nivel_logro_AD' => 0,
                    'nivel_logro_B' => 0,
                    'nivel_logro_C' => 0,
                ];

                foreach ($items as $item) {
                    $result['nivel_logro_' . $item->nivel_logro] = (int) $item->total/11;
                }

                return $result;
            })
            ->values();



        $logros = DB::table('notas_por_competencias')
        ->select(
            'nivel_logro',
            DB::raw('COUNT(*) as total')
        )
            //->where('codigo_estudiante', "0322")
            ->whereNotNull('nivel_logro')
            ->groupBy('nivel_logro')
            ->get()
            ->map(function ($item) {
                $item->total = (int) $item->total;
                return $item;
            });

        $matriculadosQuery = DB::table('estudiante_secciones')
        ->join('grados', 'estudiante_secciones.id_grado', '=', 'grados.id_grado')
        ->join('secciones', 'estudiante_secciones.id_seccion', '=', 'secciones.id_seccion')
        ->join('estudiantes', 'estudiante_secciones.codigo_estudiante', '=', 'estudiantes.codigo_estudiante') // Nueva unión
        ->select(
            DB::raw("CONCAT(grados.detalle, ' ', secciones.detalle) as grado_seccion"),
            DB::raw('COUNT(DISTINCT estudiante_secciones.codigo_estudiante) as total_estudiantes')
        )
        ->groupBy('grado_seccion');


        // Aplicar filtros si existen
        if ($nivel2) {
            $matriculadosQuery->where('grados.id_nivel', $nivel2);
        }
        if ($grado2) {
            $matriculadosQuery->where('estudiante_secciones.id_grado', $grado2);
        }
        if ($fechaInicio && $fechaFin) {
            $fechaInicio .= ' 00:00:00';
            $fechaFin .= ' 23:59:59';
            $matriculadosQuery->whereBetween('estudiantes.created_at', [$fechaInicio, $fechaFin]);
        }


        $matriculados = $matriculadosQuery->get();

        // Combinando ambas respuestas en un solo JSON
        $response = [
            'asistencias' => $asis,
            'logros' => $logros,
            'logrosPorGradoSeccion' => $logrosPorGradoSeccion,
            'matriculados' => $matriculados
        ];

        return response()->json($response, 200);
    }
}
