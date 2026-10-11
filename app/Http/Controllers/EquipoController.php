<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipoRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Equipo;
use Illuminate\Http\JsonResponse;

class EquipoController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::success(Equipo::orderBy('codigo')->paginate(15), 'Equipos obtenidos correctamente');
    }

    public function store(EquipoRequest $request): JsonResponse
    {
        $equipo = Equipo::create($request->validated())->refresh();

        return ApiResponse::success($equipo, 'Equipo creado correctamente', 201);
    }

    public function show(Equipo $equipo): JsonResponse
    {
        return ApiResponse::success($equipo, 'Equipo obtenido correctamente');
    }

    public function update(EquipoRequest $request, Equipo $equipo): JsonResponse
    {
        $equipo->update($request->validated());

        return ApiResponse::success($equipo->fresh(), 'Equipo actualizado correctamente');
    }

    public function destroy(Equipo $equipo): JsonResponse
    {
        if ($equipo->prestamos()->exists()) {
            return ApiResponse::error('No se puede eliminar un equipo con préstamos registrados', 409);
        }

        $equipo->delete();

        return ApiResponse::success(null, 'Equipo eliminado correctamente');
    }
}
