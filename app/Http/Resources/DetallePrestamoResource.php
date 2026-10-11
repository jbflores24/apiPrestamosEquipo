<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetallePrestamoResource extends JsonResource
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
            'prestamo_id' => $this->prestamo_id,
            'equipo_id' => $this->equipo_id,
            'equipo' => new EquipoResource($this->whenLoaded('equipo')),
            'fecha_devolucion_real' => $this->fecha_devolucion_real,
            'observaciones' => $this->observaciones,
            'dias_retraso' => $this->dias_retraso,
        ]);
    }
}
