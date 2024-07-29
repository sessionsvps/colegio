<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Bimestre;
use App\Models\Estudiante_Seccion;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        $bimestres = Bimestre::all();
        $estudiante = null;
        $asistencia = null;

        if ($request->filled('codigo_estudiante') && $request->filled('bimestre')) {
            $estudiante = Estudiante_Seccion::where('codigo_estudiante', $request->input('codigo_estudiante'))->first();
            $asistencia = Asistencia::where('codigo_estudiante', $request->input('codigo_estudiante'))
            ->where('id_bimestre', $request->input('bimestre'))->first();
        }

        return view('asistencias.index',compact('bimestres','estudiante','asistencia'));
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
