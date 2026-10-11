<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrestamoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ([
            'id' => $this->id,
            'alumno_id' => $this->alumno_id,
            'alumno' => new AlumnoResource($this->whenLoaded('alumno')),
            'fecha_salida' => $this->fecha_salida,
            'fecha_devolucion_esperada' => $this->fecha_devolucion_esperada,
            'detalles' => new DetallePrestamoCollection($this->whenLoaded('detalles')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
    }
}
