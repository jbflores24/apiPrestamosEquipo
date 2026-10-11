<?php

namespace App\Http\Controllers;

use App\Http\Requests\DevolucionRequest;
use App\Http\Requests\PrestamoRequest;
use App\Http\Resources\PrestamoCollection;
use App\Http\Resources\PrestamoResource;
use App\Http\Responses\ApiResponse;
use App\Models\Equipo;
use App\Models\Prestamo;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrestamoController extends Controller
{
    const RELACIONES = ['alumno', 'detalles.equipo'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $prestamos = new PrestamoCollection(
                Prestamo::with(self::RELACIONES)->orderBy('fecha_salida', 'desc')->paginate(15)
            );
            return ApiResponse::success('Listado de préstamos', 200, $prestamos);
        } catch (Exception $e) {
            Log::error('Error al listar préstamos', ['exception' => $e]);
            return ApiResponse::error('No se pudo obtener el listado de préstamos', 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PrestamoRequest $request)
    {
        try {
            $datos = $request->validated();
            $prestamo = DB::transaction(function () use ($datos) {
                $equipos = Equipo::whereIn('id', $datos['equipos'])->lockForUpdate()->get();
                if ($equipos->contains(fn ($equipo) => $equipo->estado !== Equipo::DISPONIBLE)) {
                    return null;
                }
                $prestamo = Prestamo::create([
                    'alumno_id' => $datos['alumno_id'],
                    'fecha_salida' => $datos['fecha_salida'],
                    'fecha_devolucion_esperada' => $datos['fecha_devolucion_esperada']
                ]);
                $prestamo->equipos()->attach($equipos->pluck('id'));
                Equipo::whereIn('id', $equipos->pluck('id'))->update(['estado' => Equipo::PRESTADO]);
                return $prestamo;
            });
            if ($prestamo === null) {
                return ApiResponse::error('Uno o más equipos ya no están disponibles', 422);
            }
            return ApiResponse::success('Préstamo registrado correctamente', 201, new PrestamoResource($prestamo->load(self::RELACIONES)));
        } catch (Exception $e) {
            Log::error('Error al registrar préstamo', ['exception' => $e]);
            return ApiResponse::error('No se pudo registrar el préstamo', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Prestamo $prestamo)
    {
        try {
            return ApiResponse::success('Información del préstamo', 200, new PrestamoResource($prestamo->load(self::RELACIONES)));
        } catch (Exception $e) {
            Log::error('Error al consultar préstamo', ['exception' => $e]);
            return ApiResponse::error('No se pudo obtener el préstamo', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PrestamoRequest $request, Prestamo $prestamo)
    {
        try {
            $prestamo->update($request->validated());
            return ApiResponse::success('Préstamo actualizado correctamente', 200, new PrestamoResource($prestamo->refresh()->load(self::RELACIONES)));
        } catch (Exception $e) {
            Log::error('Error al actualizar préstamo', ['exception' => $e]);
            return ApiResponse::error('No se pudo actualizar el préstamo', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prestamo $prestamo)
    {
        try {
            DB::transaction(function () use ($prestamo) {
                $pendientes = $prestamo->detalles()->whereNull('fecha_devolucion_real')->pluck('equipo_id');
                Equipo::whereIn('id', $pendientes)->update(['estado' => Equipo::DISPONIBLE]);
                $prestamo->delete();
            });
            return ApiResponse::success('Préstamo eliminado correctamente', 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar préstamo', ['exception' => $e]);
            return ApiResponse::error('No se pudo eliminar el préstamo', 500);
        }
    }

    /**
     * Register the return of the equipment of a loan.
     */
    public function devolucion(DevolucionRequest $request, Prestamo $prestamo)
    {
        try {
            $datos = $request->validated();
            DB::transaction(function () use ($prestamo, $datos) {
                $pendientes = $prestamo->detalles()->whereNull('fecha_devolucion_real');
                if (isset($datos['equipos'])) {
                    $pendientes->whereIn('equipo_id', $datos['equipos']);
                }
                $equipoIds = $pendientes->pluck('equipo_id');
                $cambios = ['fecha_devolucion_real' => now()->toDateString()];
                if (array_key_exists('observaciones', $datos)) {
                    $cambios['observaciones'] = $datos['observaciones'];
                }
                $prestamo->detalles()
                    ->whereNull('fecha_devolucion_real')
                    ->whereIn('equipo_id', $equipoIds)
                    ->update($cambios);
                Equipo::whereIn('id', $equipoIds)->update(['estado' => Equipo::DISPONIBLE]);
            });
            return ApiResponse::success('Devolución registrada correctamente', 200, new PrestamoResource($prestamo->refresh()->load(self::RELACIONES)));
        } catch (Exception $e) {
            Log::error('Error al registrar devolución', ['exception' => $e]);
            return ApiResponse::error('No se pudo registrar la devolución', 500);
        }
    }
}
