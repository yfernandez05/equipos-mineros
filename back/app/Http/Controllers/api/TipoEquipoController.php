<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TipoEquipo\StoreTipoEquipoRequest;
use App\Http\Requests\Api\TipoEquipo\UpdateTipoEquipoRequest;
use App\Http\Resources\Api\TipoEquipoResource;
use App\Models\TipoEquipo;
use App\Services\TipoEquipoService;
use App\Utils\RuleManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TipoEquipoController extends BaseController
{
    protected TipoEquipoService $tipoEquipoService;

    public function __construct(TipoEquipoService $tipoEquipoService)
    {
        $this->tipoEquipoService = $tipoEquipoService;
    }

    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new TipoEquipo());
        $perPage = $this->getLimitPagination($request);

        return $this->tipoEquipoService->getAllPaginated($perPage, $filters);
    }

    public function store(StoreTipoEquipoRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['userinsert'] = $request->user()?->email;

        $tipoEquipo = $this->tipoEquipoService->createTipoEquipo($data);

        return response()->json([
            'message' => 'Tipo de equipo creado con éxito',
            'data' => new TipoEquipoResource($tipoEquipo)
        ], 201);
    }

    public function show(TipoEquipo $tipoEquipo): JsonResponse
    {
        return response()->json([
            'data' => new TipoEquipoResource($tipoEquipo)
        ], 200);
    }

    public function update(UpdateTipoEquipoRequest $request, TipoEquipo $tipoEquipo): JsonResponse {

        $data = $request->validated();
        $data['userupdate'] = $request->user()?->email;

        $updatedTipoEquipo = $this->tipoEquipoService->updateTipoEquipo(
            $tipoEquipo,
            $data
        );

        return response()->json([
            'message' => 'Tipo de equipo actualizado con éxito',
            'data' => new TipoEquipoResource($updatedTipoEquipo)
        ], 200);
    }

    public function destroy(TipoEquipo $tipoEquipo): JsonResponse
    {
        $this->tipoEquipoService->updateStatusTipoEquipo($tipoEquipo, RuleManager::DISABLED_STATE);

        return response()->json(['message' => 'Tipo de equipo desactivado con éxito'], 200);
    }

    public function getSelect()
    {
        $result = $this->tipoEquipoService->getSelect();

        return response()->json([
            'data' => $result
        ]);
    }
}
