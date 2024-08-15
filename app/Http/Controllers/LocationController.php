<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    // public function getDepartamentos(Request $request)
    // {
    //     $departamentos = DB::table('departamentos')->orderBy('nombre')->get();
    //     return response()->json($departamentos);
    // }

    public function getProvincias($id)
    {
        $departamento_id = $id;
        $provincias = DB::table('provincias')
                        ->where('departamento_id', $departamento_id)
                        ->orderBy('nombre')
                        ->get();
        return response()->json($provincias);                        
    }

    public function getDistritos($id)
    {
        $provincia_id = $id;
        $distritos = DB::table('distritos')
                       ->where('provincia_id', $provincia_id)
                       ->orderBy('nombre')
                       ->get();
        return response()->json($distritos);
    }
}
