<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MantenimientoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'equipo_id' => $this->equipo_id,
            'equipo' => [
                'id' => $this->equipo->id,
                'codigo' => $this->equipo->codigo,
            ],
            'horometro' => $this->horometro,
            'fecha_mantenimiento' => $this->fecha_mantenimiento,
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
        ];
    }
}
