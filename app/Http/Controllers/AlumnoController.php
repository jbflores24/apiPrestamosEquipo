<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlumnoRequest;
use App\Http\Resources\AlumnoCollection;
use App\Http\Resources\AlumnoResource;
use App\Http\Responses\ApiResponse;
use App\Models\Alumno;
use Exception;
use Illuminate\Support\Facades\Log;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $alumnos = new AlumnoCollection(Alumno::orderBy('nombre', 'asc')->paginate(15));
            return ApiResponse::success('Listado de alumnos', 200, $alumnos);
        } catch (Exception $e) {
            Log::error('Error al listar alumnos', ['exception' => $e]);
            return ApiResponse::error('No se pudo obtener el listado de alumnos', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlumnoRequest $request)
    {
        try {
            $alumno = Alumno::create($request->validated())->refresh();
            return ApiResponse::success('Alumno creado correctamente', 201, new AlumnoResource($alumno->load('prestamos')));
        } catch (Exception $e) {
            Log::error('Error al crear alumno', ['exception' => $e]);
            return ApiResponse::error('No se pudo crear el alumno', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Alumno $alumno)
    {
        try {
            return ApiResponse::success('Información del alumno', 200, new AlumnoResource($alumno->load('prestamos')));
        } catch (Exception $e) {
            Log::error('Error al consultar alumno', ['exception' => $e]);
            return ApiResponse::error('No se pudo obtener el alumno', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlumnoRequest $request, Alumno $alumno)
    {
        try {
            $alumno->update($request->validated());
            return ApiResponse::success('Alumno actualizado correctamente', 200, new AlumnoResource($alumno->refresh()));
        } catch (Exception $e) {
            Log::error('Error al actualizar alumno', ['exception' => $e]);
            return ApiResponse::error('No se pudo actualizar el alumno', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumno $alumno)
    {
        try {
            if ($alumno->prestamos()->exists()) {
                return ApiResponse::error('No se puede eliminar un alumno con préstamos registrados', 409);
            }
            $alumno->delete();
            return ApiResponse::success('Alumno eliminado correctamente', 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar alumno', ['exception' => $e]);
            return ApiResponse::error('No se pudo eliminar el alumno', 500);
        }
    }
}
