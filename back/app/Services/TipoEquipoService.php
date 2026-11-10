<?php

namespace App\Services;

use App\Models\TipoEquipo;
use App\Utils\RuleManager;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class TipoEquipoService
{
    public function __construct()
    {
        //
    }

    private function setModel(TipoEquipo $tipoEquipo, array $data): TipoEquipo
    {
        $tipoEquipo->fill($data);
        return $tipoEquipo;
    }

    public function getAllPaginated(int $perPage, array $filters): LengthAwarePaginator
    {
        return TipoEquipo::where($filters)
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function getById(int $id): TipoEquipo
    {
        return TipoEquipo::where('estado', RuleManager::ACTIVE_STATE)
            ->findOrFail($id);
    }

    public function createTipoEquipo(array $data): TipoEquipo
    {
        $tipoEquipo = $this->setModel(new TipoEquipo(), $data);

        $tipoEquipo->estado = RuleManager::ACTIVE_STATE;
        $tipoEquipo->save();

        return $tipoEquipo;
    }

    public function updateTipoEquipo(TipoEquipo $tipoEquipo, array $data): TipoEquipo
    {
        $tipoEquipo = $this->setModel($tipoEquipo, $data);
        $tipoEquipo->save();

        return $tipoEquipo;
    }

    public function updateStatusTipoEquipo(TipoEquipo $tipoEquipo, string $status): TipoEquipo
    {
        if ($status === RuleManager::DISABLED_STATE && $tipoEquipo->equipos()->where('estado', RuleManager::ACTIVE_STATE)->exists()) {

            throw ValidationException::withMessages([
                'estado' => 'No se puede desactivar el tipo porque tiene equipos activos.'
            ]);
        }

        $tipoEquipo->update(['estado' => $status]);

        return $tipoEquipo;
    }

    public function getSelect()
    {
        return TipoEquipo::query()
            ->select('id', 'nombre')
            ->where('estado', RuleManager::ACTIVE_STATE)
            ->orderBy('nombre')
            ->get();
    }
}