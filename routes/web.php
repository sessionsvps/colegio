<?php

use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\ExportController;
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
    Route::resource('docentes', DocenteController::class)->except('show');
    Route::resource('users', UserController::class)->except('show');
    Route::get('/export',[ExportController::class,'export'])->name('export');
    Route::get('/export-pdf', [ExportController::class, 'exportPdf'])->name('exportPdf');
});
