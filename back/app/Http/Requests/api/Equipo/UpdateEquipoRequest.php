<?php

namespace App\Http\Requests\Api\Equipo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEquipoRequest extends FormRequest
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
            'codigo' => [
                'required',
                'string',
                'max:30',
                Rule::unique('equipos', 'codigo')->ignore($this->route('equipo')),
            ],
            'tipo_equipo_id' => [
                'required',
                'integer',
                Rule::exists('tipos_equipos', 'id')->where('estado', 'A')
            ],
            'horometro_actual' => [
                'required',
                'numeric',
                'min:0'
            ]
        ];
    }
}
