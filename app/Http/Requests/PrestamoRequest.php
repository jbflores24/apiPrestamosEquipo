<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PrestamoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $crear = $this->isMethod('POST');
        $regla = $crear ? 'required' : 'sometimes';

        $reglas = [
            'alumno_id' => [$regla, 'integer', Rule::exists('alumnos', 'id')],
            'fecha_salida' => [$regla, 'date_format:Y-m-d'],
            'fecha_devolucion_esperada' => [$regla, 'date_format:Y-m-d', 'after_or_equal:fecha_salida'],
        ];

        if ($crear) {
            $reglas['equipos'] = ['required', 'array', 'min:1'];
            $reglas['equipos.*'] = [
                'integer',
                'distinct',
                Rule::exists('equipos', 'id')->where('estado', 'disponible'),
            ];
        }

        return $reglas;
    }

    public function messages(): array
    {
        return [
            'equipos.*.exists' => 'El equipo seleccionado no existe o no está disponible.',
        ];
    }
}
