<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AlumnoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $regla = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'matricula' => [$regla, 'string', 'max:20', Rule::unique('alumnos', 'matricula')->ignore($this->route('alumno'))],
            'nombre' => [$regla, 'string', 'max:150'],
            'carrera' => [$regla, 'string', 'max:150'],
        ];
    }
}
