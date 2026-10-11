<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipoRequest extends FormRequest
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
        $regla = $this->isMethod('POST') ? 'required' : 'sometimes|required';
        return [
            'codigo' => [$regla, 'max:30', Rule::unique('equipos', 'codigo')->ignore($this->route('equipo'))],
            'nombre' => $regla.'|min:3|max:150',
            'tipo' => $regla.'|min:3|max:80',
            'estado' => 'sometimes|in:disponible,mantenimiento'
        ];
    }
}
