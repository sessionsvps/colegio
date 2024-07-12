<?php

use App\Http\Controllers\CursoController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocenteController;
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
});
