<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AvisoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\PrestamoController;
use Illuminate\Support\Facades\Route;

Route::apiResource('avisos', AvisoController::class)->parameters(['avisos' => 'aviso']);
Route::apiResource('alumnos', AlumnoController::class)->parameters(['alumnos' => 'alumno']);
Route::apiResource('equipos', EquipoController::class)->parameters(['equipos' => 'equipo']);
Route::apiResource('prestamos', PrestamoController::class)->parameters(['prestamos' => 'prestamo']);
Route::post('prestamos/{prestamo}/devolucion', [PrestamoController::class, 'devolver']);
