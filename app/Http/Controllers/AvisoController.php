<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvisoRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Aviso;
use Illuminate\Http\JsonResponse;

class AvisoController extends Controller
{
    public function index(): JsonResponse
    {
        return ApiResponse::success(Aviso::orderBy('fecha')->paginate(15), 'Avisos obtenidos correctamente');
    }

    public function store(AvisoRequest $request): JsonResponse
    {
        $aviso = Aviso::create($request->validated());

        return ApiResponse::success($aviso, 'Aviso creado correctamente', 201);
    }

    public function show(Aviso $aviso): JsonResponse
    {
        return ApiResponse::success($aviso, 'Aviso obtenido correctamente');
    }

    public function update(AvisoRequest $request, Aviso $aviso): JsonResponse
    {
        $aviso->update($request->validated());

        return ApiResponse::success($aviso->fresh(), 'Aviso actualizado correctamente');
    }

    public function destroy(Aviso $aviso): JsonResponse
    {
        $aviso->delete();

        return ApiResponse::success(null, 'Aviso eliminado correctamente');
    }
}
