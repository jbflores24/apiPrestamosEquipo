<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DevolucionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'equipos' => 'sometimes|array|min:1',
            'equipos.*' => [
                'integer',
                'distinct',
                Rule::exists('detalle_prestamos', 'equipo_id')
                    ->where('prestamo_id', $this->route('prestamo')->id)
                    ->whereNull('fecha_devolucion_real')
            ],
            'observaciones' => 'nullable|string|max:255'
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'equipos.*.exists' => 'El equipo no pertenece a este préstamo o ya fue devuelto.'
        ];
    }
}
