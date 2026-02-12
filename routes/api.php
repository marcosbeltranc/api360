<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OptionListController;
use App\Http\Controllers\InfrastructureDeviceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- RUTAS PÚBLICAS ---
Route::post('/login', [AuthController::class, 'login']);


// --- RUTAS PROTEGIDAS (Requieren Token) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Esto crea: GET /users, POST /users, GET /users/{id}, PUT /users/{id}, DELETE /users/{id}
    Route::apiResource('users', UserController::class);

    // Ruta de perfil (la que venía por defecto, la mantenemos)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/options', [OptionListController::class, 'get']);      // Consultar
    Route::post('/options', [OptionListController::class, 'create']);   // Crear
    Route::put('/options/{id}', [OptionListController::class, 'update']); // Editar
    Route::delete('/options/{id}', [OptionListController::class, 'delete']); // Eliminar

    Route::get('/infrastructure', [InfrastructureDeviceController::class, 'get']);
    Route::post('/infrastructure', [InfrastructureDeviceController::class, 'create']);
    Route::put('/infrastructure/{id}', [InfrastructureDeviceController::class, 'update']);
    Route::delete('/infrastructure/{id}', [InfrastructureDeviceController::class, 'delete']);
});