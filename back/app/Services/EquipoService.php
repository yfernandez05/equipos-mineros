<?php

namespace App\Services;

use App\Models\Equipo;
use App\Utils\RuleManager;
use Illuminate\Pagination\LengthAwarePaginator;

class EquipoService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    private function setModel(Equipo $equipo, array $data): Equipo
    {
        $equipo->fill($data);
        return $equipo;
    }

    public function getAllPaginated(int $perPage, array $filters): LengthAwarePaginator
    {
        return Equipo::where($filters)
            ->with('tipoEquipo')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function getById(int $id): Equipo
    {
        return Equipo::where('estado', RuleManager::ACTIVE_STATE)
            ->with('tipoEquipo')
            ->findOrFail($id);
    }

    public function createEquipo(array $data): Equipo
    {
        $equipo = $this->setModel(new Equipo(), $data);

        $equipo->estado = RuleManager::ACTIVE_STATE;
        $equipo->save();

        $equipo->load('tipoEquipo');

        return $equipo;
    }

    public function updateEquipo(Equipo $equipo, array $data): Equipo
    {
        $equipo = $this->setModel($equipo, $data);
        $equipo->save();

        $equipo->load('tipoEquipo');

        return $equipo;
    }

    public function updateStatusEquipo(Equipo $equipo, string $status): Equipo
    {
        $equipo->update(['estado' => $status]);

        return $equipo;
    }
}
