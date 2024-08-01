<?php

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\CatedraController;
use App\Http\Controllers\BoletaNotaController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\ExoneracionController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('inicio');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('estudiantes',EstudianteController::class)->except('show');
    Route::get('/matriculas',[EstudianteController::class,'matricular'])->name('estudiantes.matricular');
    Route::post('/matriculado',[EstudianteController::class,'realizarMatricula'])->name('estudiantes.realizarMatricula');
    Route::get('/estudiantes/{codigo_curso}/{nivel}/{grado}/{seccion}', [EstudianteController::class, 'vista_docente'])->name('estudiantes.filtrar-por-aula');
    
    Route::get('catedras/create/{codigo_curso}/{nivel}/{grado}/{seccion}', [CatedraController::class, 'create'])->name('catedras.custom-create');
    Route::get('catedras/edit/{codigo_curso}/{nivel}/{grado}/{seccion}', [CatedraController::class, 'edit'])->name('catedras.custom-edit');
    Route::put('catedras/update/{codigo_curso}/{nivel}/{grado}/{seccion}', [CatedraController::class, 'update'])->name('catedras.custom-update');
    Route::delete('catedras/{codigo_curso}/{nivel}/{grado}/{seccion}', [CatedraController::class, 'destroy'])->name('catedras.custom-delete');
    Route::get('catedras/cancel/{nivel}/{grado}/{seccion}', [CatedraController::class, 'cancelar'])->name('catedras.cancelar');
    Route::resource('catedras', CatedraController::class)->except('show');
    
    Route::resource('docentes', DocenteController::class)->except('show');
    Route::resource('users', UserController::class)->except('show');
    Route::resource('cursos', CursoController::class)->except('show');
    Route::get('cursos/{id}/info', [CursoController::class, 'info'])->name('cursos.info');
    Route::get('cursos/{id}/info-docente', [CursoController::class, 'info_docente'])->name('cursos.info-docente');
    Route::get('/malla',[CursoController::class,'mallaCurricular'])->name('cursos.malla');
    Route::resource('boleta_notas', BoletaNotaController::class)->except('show');
    Route::get('/notas/{codigo_estudiante}/{codigo_curso}/{año_escolar}/edit', [BoletaNotaController::class, 'edit'])->name('boleta_notas.edit');
    Route::put('/notas/{codigo_estudiante}/{codigo_curso}/{año_escolar}', [BoletaNotaController::class, 'update'])->name('boleta_notas.update');
    Route::resource('asistencias', AsistenciaController::class)->except('show');
    Route::resource('exoneraciones', ExoneracionController::class)->except('show');
    Route::get('/exoneraciones/{codigo_estudiante}/{año_escolar}/edit', [ExoneracionController::class, 'edit'])->name('exoneraciones.edit');
    Route::put('/exoneraciones/{codigo_estudiante}/{año_escolar}', [ExoneracionController::class, 'update'])->name('exoneraciones.update');
    Route::get('/asistencias/{codigo_estudiante}/{id_bimestre}/edit', [AsistenciaController::class, 'edit'])->name('asistencias.edit');
    Route::put('/asistencias/{codigo_estudiante}/{id_bimestre}', [AsistenciaController::class, 'update'])->name('asistencias.update');
    Route::get('/export',[ExportController::class,'export'])->name('export');
    Route::get('/export-pdf', [ExportController::class, 'exportPdf'])->name('exportPdf');
});
