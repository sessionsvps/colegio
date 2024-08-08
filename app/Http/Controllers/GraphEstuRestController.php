<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraphEstuRestController extends Controller
{
    public function index()
    {
        $asis = DB::table('bimestres')
        ->join('asistencias', 'bimestres.id', '=', 'asistencias.id_bimestre')
        ->select(
            'bimestres.descripcion as bimestre',
            DB::raw('SUM(asistencias.inasistencias_justificadas) as inasistencias_justificadas'),
            DB::raw('SUM(asistencias.inasistencias_injustificadas) as inasistencias_injustificadas'),
            DB::raw('SUM(asistencias.tardanzas_justificadas) as tardanzas_justificadas'),
            DB::raw('SUM(asistencias.tardanzas_injustificadas) as tardanzas_injustificadas')
        )
            ->groupBy('bimestres.descripcion')
            ->get()
            ->map(function ($item) {
                $item->inasistencias_justificadas = (int) $item->inasistencias_justificadas;
                $item->inasistencias_injustificadas = (int) $item->inasistencias_injustificadas;
                $item->tardanzas_justificadas = (int) $item->tardanzas_justificadas;
                $item->tardanzas_injustificadas = (int) $item->tardanzas_injustificadas;
                return $item;
            });

        $logros = DB::table('notas_por_competencias')
        ->select(
            'nivel_logro',
            DB::raw('COUNT(*) as total')
        )
            ->whereNotNull('nivel_logro')
            ->groupBy('nivel_logro')
            ->get()
            ->map(function ($item) {
                $item->total = (int) $item->total;
                return $item;
            });

        // Combinando ambas respuestas en un solo JSON
        $response = [
            'asistencias' => $asis,
            'logros' => $logros
        ];


        return response()->json($response, 200);
    }
}
