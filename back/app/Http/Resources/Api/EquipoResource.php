<?php

namespace App\Http\Resources\Api;

use App\Utils\RuleManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $horasTrabajadas = (float) $this->horometro_actual - (float) $this->horometro_ultimo_mantenimiento;

        $intervalo = (int) $this->tipoEquipo->intervalo_mantenimiento;

        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'tipo_equipo_id' => $this->tipo_equipo_id,
            'tipoequipo' => [
                'id' => $this->tipoEquipo->id,
                'nombre' => $this->tipoEquipo->nombre,
                'intervalo_mantenimiento' => $intervalo,
            ],

            'horometro_actual' => $this->horometro_actual,
            'horometro_ultimo_mantenimiento' => $this->horometro_ultimo_mantenimiento,
            'horas_desde_mantenimiento' => $horasTrabajadas,
            'horas_restantes' => max(0, $intervalo - $horasTrabajadas),
            'estado_mantenimiento' => $horasTrabajadas >= $intervalo ? 'Requiere mantenimiento' : 'Operativo',
            'estado' => $this->estado,
            'statename' => $this->estado === RuleManager::ACTIVE_STATE ? 'Activo' : 'Inactivo',
            'isactive' => $this->estado === RuleManager::ACTIVE_STATE,
        ];
    }
}
