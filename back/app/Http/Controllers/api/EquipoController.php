<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\Equipo\StoreEquipoRequest;
use App\Http\Requests\api\Equipo\UpdateEquipoRequest;
use App\Http\Resources\api\EquipoResource;
use App\Models\Equipo;
use App\Services\EquipoService;
use App\Utils\RuleManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EquipoController extends BaseController
{
    protected EquipoService $equipoService;

    public function __construct(EquipoService $equipoService)
    {
        $this->equipoService = $equipoService;
    }

    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Equipo());
        $perPage = $this->getLimitPagination($request);

        $equipos = $this->equipoService->getAllPaginated($perPage, $filters);

        $equipos->getCollection()->transform(function ($equipo) use ($request) {
            return (new EquipoResource($equipo))->resolve($request);
        });

        return $equipos;
    }

    public function store(StoreEquipoRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['userinsert'] = $request->user()?->email;

        $equipo = $this->equipoService->createEquipo($data);

        return response()->json([
            'message' => 'Equipo creado con éxito',
            'data' => new EquipoResource($equipo)
        ], 201);
    }

    public function show(Equipo $equipo): JsonResponse
    {
        $equipo->load('tipoEquipo');

        return response()->json([
            'data' => new EquipoResource($equipo)
        ], 200);
    }

    public function update(UpdateEquipoRequest $request, Equipo $equipo): JsonResponse 
    {
        $data = $request->validated();
        $data['userupdate'] = $request->user()?->email;

        $updatedEquipo = $this->equipoService->updateEquipo($equipo, $data);

        return response()->json([
            'message' => 'Equipo actualizado con éxito',
            'data' => new EquipoResource($updatedEquipo)
        ], 200);
    }

    public function destroy(Equipo $equipo): JsonResponse
    {
        $this->equipoService->updateStatusEquipo($equipo, RuleManager::DISABLED_STATE);
        return response()->json(['message' => 'Equipo desactivado con éxito'], 200);
    }
}
