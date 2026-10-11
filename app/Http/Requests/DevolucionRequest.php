<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DevolucionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $prestamo = $this->route('prestamo');

        return [
            'equipos' => ['sometimes', 'array', 'min:1'],
            'equipos.*' => [
                'integer',
                'distinct',
                Rule::exists('detalle_prestamos', 'equipo_id')
                    ->where('prestamo_id', $prestamo->id)
                    ->whereNull('fecha_devolucion_real'),
            ],
            'observaciones' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'equipos.*.exists' => 'El equipo no pertenece a este préstamo o ya fue devuelto.',
        ];
    }
}
