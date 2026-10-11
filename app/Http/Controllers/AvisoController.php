<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvisoRequest;
use App\Http\Resources\AvisoCollection;
use App\Http\Resources\AvisoResource;
use App\Http\Responses\ApiResponse;
use App\Models\Aviso;
use Exception;
use Illuminate\Support\Facades\Log;

class AvisoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $avisos = new AvisoCollection(Aviso::orderBy('fecha', 'desc')->paginate(15));
            return ApiResponse::success('Listado de avisos', 200, $avisos);
        } catch (Exception $e) {
            Log::error('Error al listar avisos', ['exception' => $e]);
            return ApiResponse::error('No se pudo obtener el listado de avisos', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AvisoRequest $request)
    {
        try {
            $aviso = Aviso::create($request->validated())->refresh();
            return ApiResponse::success('Aviso creado correctamente', 201, new AvisoResource($aviso));
        } catch (Exception $e) {
            Log::error('Error al crear aviso', ['exception' => $e]);
            return ApiResponse::error('No se pudo crear el aviso', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Aviso $aviso)
    {
        try {
            return ApiResponse::success('Información del aviso', 200, new AvisoResource($aviso));
        } catch (Exception $e) {
            Log::error('Error al consultar aviso', ['exception' => $e]);
            return ApiResponse::error('No se pudo obtener el aviso', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AvisoRequest $request, Aviso $aviso)
    {
        try {
            $aviso->update($request->validated());
            return ApiResponse::success('Aviso actualizado correctamente', 200, new AvisoResource($aviso->refresh()));
        } catch (Exception $e) {
            Log::error('Error al actualizar aviso', ['exception' => $e]);
            return ApiResponse::error('No se pudo actualizar el aviso', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aviso $aviso)
    {
        try {
            $aviso->delete();
            return ApiResponse::success('Aviso eliminado correctamente', 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar aviso', ['exception' => $e]);
            return ApiResponse::error('No se pudo eliminar el aviso', 500);
        }
    }
}
