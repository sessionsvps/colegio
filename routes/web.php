<?php

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\NotasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
    Route::resource('docentes', DocenteController::class)->except('show');
    Route::resource('users', UserController::class)->except('show');
    Route::resource('cursos', CursoController::class)->except('show');
    Route::get('cursos/{id}/info', [CursoController::class, 'info'])->name('cursos.info');
    Route::get('/malla',[CursoController::class,'mallaCurricular'])->name('cursos.malla');
    Route::resource('notas', NotasController::class)->except('show');
    Route::resource('asistencias', AsistenciaController::class)->except('show');
    Route::get('/export',[ExportController::class,'export'])->name('export');
    Route::get('/export-pdf', [ExportController::class, 'exportPdf'])->name('exportPdf');
});
