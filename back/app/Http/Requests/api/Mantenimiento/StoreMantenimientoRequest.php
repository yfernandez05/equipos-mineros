<?php

namespace App\Http\Requests\Api\Mantenimiento;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMantenimientoRequest extends FormRequest
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
            'equipo_id' => [
                'required',
                'integer',
                Rule::exists('equipos', 'id')->where('estado', 'A')
            ],
            'horometro' => [
                'required',
                'numeric',
                'min:0'
            ],
            'fecha_mantenimiento' => [
                'required',
                'date_format:Y-m-d H:i:s',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:500'
            ]
        ];
    }
}
