<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipoRequest;
use App\Http\Resources\EquipoCollection;
use App\Http\Resources\EquipoResource;
use App\Http\Responses\ApiResponse;
use App\Models\Equipo;
use Exception;
use Illuminate\Support\Facades\Log;

class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $equipos = new EquipoCollection(Equipo::orderBy('codigo', 'asc')->paginate(15));
            return ApiResponse::success('Listado de equipos', 200, $equipos);
        } catch (Exception $e) {
            Log::error('Error al listar equipos', ['exception' => $e]);
            return ApiResponse::error('No se pudo obtener el listado de equipos', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EquipoRequest $request)
    {
        try {
            $equipo = Equipo::create($request->validated())->refresh();
            return ApiResponse::success('Equipo creado correctamente', 201, new EquipoResource($equipo));
        } catch (Exception $e) {
            Log::error('Error al crear equipo', ['exception' => $e]);
            return ApiResponse::error('No se pudo crear el equipo', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipo $equipo)
    {
        try {
            return ApiResponse::success('Información del equipo', 200, new EquipoResource($equipo));
        } catch (Exception $e) {
            Log::error('Error al consultar equipo', ['exception' => $e]);
            return ApiResponse::error('No se pudo obtener el equipo', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EquipoRequest $request, Equipo $equipo)
    {
        try {
            if ($equipo->estado === Equipo::PRESTADO) {
                return ApiResponse::error('No se puede modificar un equipo que está prestado', 409);
            }
            $equipo->update($request->validated());
            return ApiResponse::success('Equipo actualizado correctamente', 200, new EquipoResource($equipo->refresh()));
        } catch (Exception $e) {
            Log::error('Error al actualizar equipo', ['exception' => $e]);
            return ApiResponse::error('No se pudo actualizar el equipo', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipo $equipo)
    {
        try {
            if ($equipo->prestamos()->exists()) {
                return ApiResponse::error('No se puede eliminar un equipo con préstamos registrados', 409);
            }
            $equipo->delete();
            return ApiResponse::success('Equipo eliminado correctamente', 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar equipo', ['exception' => $e]);
            return ApiResponse::error('No se pudo eliminar el equipo', 500);
        }
    }
}
