<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Mantenimiento\StoreMantenimientoRequest;
use App\Http\Resources\Api\MantenimientoResource;
use App\Models\Mantenimiento;
use App\Services\MantenimientoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MantenimientoController extends BaseController
{

    protected MantenimientoService $mantenimientoService; 

    public function __construct(MantenimientoService $mantenimientoService)
    {
        $this->mantenimientoService = $mantenimientoService;
    }

    public function index(Request $request)
    {
        $filters = $this->getFilters($request, new Mantenimiento());
        $perPage = $this->getLimitPagination($request);

        return $this->mantenimientoService->getAllPaginated($perPage,$filters);
    }

    public function store(StoreMantenimientoRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['userinsert'] = $request->user()?->email;

        $mantenimiento = $this->mantenimientoService->createMantenimiento($data);

        return response()->json([
            'message' => 'Mantenimiento registrado con éxito',
            'data' => new MantenimientoResource($mantenimiento)
        ], 201);
    }

    public function porEquipo(int $equipoId)
    {
        $perPage = $this->getLimitPagination(request());

        return MantenimientoResource::collection(
            $this->mantenimientoService->getByEquipo($equipoId, $perPage)
        );
    }
}
