<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlumnoRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Alumno;
use Illuminate\Http\JsonResponse;

class AlumnoController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::success(Alumno::orderBy('nombre')->paginate(15), 'Alumnos obtenidos correctamente');
    }

    public function store(AlumnoRequest $request): JsonResponse
    {
        $alumno = Alumno::create($request->validated());

        return ApiResponse::success($alumno, 'Alumno creado correctamente', 201);
    }

    public function show(Alumno $alumno): JsonResponse
    {
        return ApiResponse::success($alumno, 'Alumno obtenido correctamente');
    }

    public function update(AlumnoRequest $request, Alumno $alumno): JsonResponse
    {
        $alumno->update($request->validated());

        return ApiResponse::success($alumno->fresh(), 'Alumno actualizado correctamente');
    }

    public function destroy(Alumno $alumno): JsonResponse
    {
        if ($alumno->prestamos()->exists()) {
            return ApiResponse::error('No se puede eliminar un alumno con préstamos registrados', 409);
        }

        $alumno->delete();

        return ApiResponse::success(null, 'Alumno eliminado correctamente');
    }
}
