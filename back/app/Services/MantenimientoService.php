<?php

namespace App\Services;

use App\Models\Equipo;
use App\Models\Mantenimiento;
use App\Utils\RuleManager;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MantenimientoService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    private function setModel(Mantenimiento $mantenimiento, array $data): Mantenimiento
    {
        $mantenimiento->fill($data);
        return $mantenimiento;
    }

    public function getAllPaginated(int $perPage, array $filters): LengthAwarePaginator
    {
        return Mantenimiento::where($filters)
            ->with('equipo.tipoEquipo')
            ->orderByDesc('fecha_mantenimiento')
            ->paginate($perPage);
    }

    public function getByEquipo(int $equipoId, int $perPage): LengthAwarePaginator
    {
        return Mantenimiento::where('equipo_id', $equipoId)
            ->where('estado', RuleManager::ACTIVE_STATE)
            ->with('equipo.tipoEquipo')
            ->orderByDesc('fecha_mantenimiento')
            ->paginate($perPage);
    }

    public function createMantenimiento(array $data): Mantenimiento
    {
        return DB::transaction(function () use ($data) {

            $equipo = Equipo::where('estado', RuleManager::ACTIVE_STATE)
                ->lockForUpdate()
                ->findOrFail($data['equipo_id']);

            $horometro = $data['horometro'];

            if ($horometro > $equipo->horometro_actual) {
                throw ValidationException::withMessages([
                    'horometro' => 'El horómetro del mantenimiento no puede superar el actual.'
                ]);
            }

            if ($horometro < $equipo->horometro_ultimo_mantenimiento) {
                throw ValidationException::withMessages([
                    'horometro' => 'El horómetro no puede ser menor al último mantenimiento.'
                ]);
            }

            $mantenimiento = $this->setModel(new Mantenimiento(), $data);

            $mantenimiento->estado = RuleManager::ACTIVE_STATE;
            $mantenimiento->save();

            $equipo->update([
                'horometro_ultimo_mantenimiento' => $horometro,
                'userupdate' => $data['userinsert'] ?? null
            ]);

            $mantenimiento->load('equipo.tipoEquipo');

            return $mantenimiento;
        });
    }
}
