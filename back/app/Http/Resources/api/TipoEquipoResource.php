<?php

namespace App\Http\Resources\Api;

use App\Utils\RuleManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TipoEquipoResource extends JsonResource
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
            'nombre' => $this->nombre,
            'intervalo_mantenimiento' => $this->intervalo_mantenimiento,
            'estado' => $this->estado,
            'statename' => $this->estado === RuleManager::ACTIVE_STATE ? 'Activo' : 'Inactivo',
            'isactive' => $this->estado === RuleManager::ACTIVE_STATE,
        ];
    }
}
