<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Equipo;
use Illuminate\Validation\Rule;

class EquipoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $regla = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'codigo' => [$regla, 'string', 'max:30', Rule::unique('equipos', 'codigo')->ignore($this->route('equipo'))],
            'nombre' => [$regla, 'string', 'max:150'],
            'tipo' => [$regla, 'string', 'max:80'],
            'estado' => ['sometimes', Rule::in([Equipo::DISPONIBLE, Equipo::MANTENIMIENTO])],
        ];
    }
}
