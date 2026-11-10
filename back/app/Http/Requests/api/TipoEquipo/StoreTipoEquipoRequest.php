<?php

namespace App\Http\Requests\Api\TipoEquipo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTipoEquipoRequest extends FormRequest
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
            'nombre' => [
                'required',
                'string',
                'max:60',
                Rule::unique('tipos_equipos', 'nombre')->where(function ($query) {
                    return $query->where('estado', 'A');
                }),
            ],
            'intervalo_mantenimiento' => [
                'required',
                'integer',
                'min:1'
            ]
        ];
    }
}
