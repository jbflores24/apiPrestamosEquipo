<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AvisoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\PrestamoController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/aviso',AvisoController::class);
Route::apiResource('/alumno',AlumnoController::class);
Route::apiResource('/equipo',EquipoController::class);
Route::apiResource('/prestamo',PrestamoController::class);
Route::post('prestamo/{prestamo}/devolucion',[PrestamoController::class,'devolucion']);
