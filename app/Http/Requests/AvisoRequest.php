<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvisoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $regla = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'titulo' => [$regla, 'string', 'max:150'],
            'texto' => [$regla, 'string'],
            'fecha' => [$regla, 'date_format:Y-m-d'],
        ];
    }
}
