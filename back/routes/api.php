<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\Api\EquipoController;
use App\Http\Controllers\Api\MantenimientoController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\StockMovementController;
use App\Http\Controllers\Api\TipoEquipoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API funcionando correctamente'
    ]);
});

// --- RUTAS PROTEGIDAS (Requieren Token Bearer) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Autenticación & Perfil
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Tipos de equipos
    Route::get('tipos-equipos/select', [TipoEquipoController::class, 'getSelect']);
    Route::apiResource('tipos-equipos', TipoEquipoController::class)->parameters(['tipos-equipos' => 'tipoEquipo']);

    // Equipos
    Route::apiResource('equipos', EquipoController::class);

    // Mantenimientos
    Route::get('mantenimientos', [MantenimientoController::class, 'index']);
    Route::post('mantenimientos', [MantenimientoController::class, 'store']);

    Route::get('mantenimientos/equipo/{equipoId}',[MantenimientoController::class, 'porEquipo']);
});