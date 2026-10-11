<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PrestamoRequest extends FormRequest
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
        $crear = $this->isMethod('POST');
        $regla = $crear ? 'required' : 'sometimes|required';
        $reglas = [
            'alumno_id' => $regla.'|integer|exists:alumnos,id',
            'fecha_salida' => $regla.'|date_format:Y-m-d',
            'fecha_devolucion_esperada' => $regla.'|date_format:Y-m-d|after_or_equal:fecha_salida'
        ];
        if ($crear) {
            $reglas['equipos'] = 'required|array|min:1';
            $reglas['equipos.*'] = ['integer', 'distinct', Rule::exists('equipos', 'id')->where('estado', 'disponible')];
        }
        return $reglas;
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'equipos.*.exists' => 'El equipo seleccionado no existe o no está disponible.'
        ];
    }
}
