<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlumnoResource extends JsonResource
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
            'matricula' => $this->matricula,
            'nombre' => $this->nombre,
            'carrera' => $this->carrera,
            'prestamos' => PrestamoResource::collection($this->whenLoaded('prestamos')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
    }
}
