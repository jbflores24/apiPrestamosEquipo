<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlumnoRequest extends FormRequest
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
            'matricula' => [$regla, 'max:20', Rule::unique('alumnos', 'matricula')->ignore($this->route('alumno'))],
            'nombre' => $regla.'|min:3|max:150',
            'carrera' => $regla.'|min:3|max:150'
        ];
    }
}
