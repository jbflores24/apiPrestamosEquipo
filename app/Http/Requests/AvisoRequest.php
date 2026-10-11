<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvisoRequest extends FormRequest
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
            'titulo' => $regla.'|min:3|max:150',
            'texto' => $regla.'|min:3',
            'fecha' => $regla.'|date_format:Y-m-d'
        ];
    }
}
