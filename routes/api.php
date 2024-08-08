<?php

use App\Http\Controllers\GraphEstuRestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('/asistencias',GraphEstuRestController::class);
//Route::get('/asistencias/{codigo_estudiante}',[GraphEstuRestController::class,'index']);

