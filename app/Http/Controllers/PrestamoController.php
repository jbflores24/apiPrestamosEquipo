<?php

namespace App\Http\Controllers;

use App\Http\Requests\DevolucionRequest;
use App\Http\Requests\PrestamoRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Equipo;
use App\Models\Prestamo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PrestamoController extends Controller
{
    private const RELACIONES = ['alumno', 'detalles.equipo'];

    public function index(): JsonResponse
    {
        $prestamos = Prestamo::with(self::RELACIONES)->latest('fecha_salida')->paginate(15);

        return ApiResponse::success($prestamos, 'Préstamos obtenidos correctamente');
    }

    public function store(PrestamoRequest $request): JsonResponse
    {
        $datos = $request->validated();

        $prestamo = DB::transaction(function () use ($datos) {
            $equipos = Equipo::whereIn('id', $datos['equipos'])->lockForUpdate()->get();

            if ($equipos->contains(fn (Equipo $equipo) => $equipo->estado !== Equipo::DISPONIBLE)) {
                throw ValidationException::withMessages([
                    'equipos' => ['Uno o más equipos ya no están disponibles.'],
                ]);
            }

            $prestamo = Prestamo::create([
                'alumno_id' => $datos['alumno_id'],
                'fecha_salida' => $datos['fecha_salida'],
                'fecha_devolucion_esperada' => $datos['fecha_devolucion_esperada'],
            ]);

            $prestamo->equipos()->attach($equipos->pluck('id'));
            Equipo::whereIn('id', $equipos->pluck('id'))->update(['estado' => Equipo::PRESTADO]);

            return $prestamo;
        });

        return ApiResponse::success($prestamo->load(self::RELACIONES), 'Préstamo registrado correctamente', 201);
    }

    public function show(Prestamo $prestamo): JsonResponse
    {
        return ApiResponse::success($prestamo->load(self::RELACIONES), 'Préstamo obtenido correctamente');
    }

    public function update(PrestamoRequest $request, Prestamo $prestamo): JsonResponse
    {
        $prestamo->update($request->validated());

        return ApiResponse::success($prestamo->fresh(self::RELACIONES), 'Préstamo actualizado correctamente');
    }

    public function destroy(Prestamo $prestamo): JsonResponse
    {
        DB::transaction(function () use ($prestamo) {
            $pendientes = $prestamo->detalles()->whereNull('fecha_devolucion_real')->pluck('equipo_id');

            Equipo::whereIn('id', $pendientes)->update(['estado' => Equipo::DISPONIBLE]);
            $prestamo->delete();
        });

        return ApiResponse::success(null, 'Préstamo eliminado correctamente');
    }

    public function devolver(DevolucionRequest $request, Prestamo $prestamo): JsonResponse
    {
        $datos = $request->validated();

        DB::transaction(function () use ($prestamo, $datos) {
            $pendientes = $prestamo->detalles()->whereNull('fecha_devolucion_real');

            if (isset($datos['equipos'])) {
                $pendientes->whereIn('equipo_id', $datos['equipos']);
            }

            $equipoIds = $pendientes->pluck('equipo_id');

            $actualizacion = ['fecha_devolucion_real' => now()->toDateString()];

            if (array_key_exists('observaciones', $datos)) {
                $actualizacion['observaciones'] = $datos['observaciones'];
            }

            $prestamo->detalles()
                ->whereNull('fecha_devolucion_real')
                ->whereIn('equipo_id', $equipoIds)
                ->update($actualizacion);

            Equipo::whereIn('id', $equipoIds)->update(['estado' => Equipo::DISPONIBLE]);
        });

        return ApiResponse::success($prestamo->fresh(self::RELACIONES), 'Devolución registrada correctamente');
    }
}
