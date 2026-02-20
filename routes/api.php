<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OptionListController;
use App\Http\Controllers\ServerDeviceController;
use App\Http\Controllers\NasDeviceController;
use App\Http\Controllers\NetworkDeviceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/users/responsibles', [UserController::class, 'getResponsibles']);
    Route::apiResource('users', UserController::class);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/options', [OptionListController::class, 'get']);
    Route::post('/options', [OptionListController::class, 'create']);
    Route::put('/options/{id}', [OptionListController::class, 'update']);
    Route::delete('/options/{id}', [OptionListController::class, 'delete']);

    Route::get('/server-devices', [ServerDeviceController::class, 'get']);
    Route::get('/server-devices/{id}', [ServerDeviceController::class, 'getById']);
    Route::post('/server-devices', [ServerDeviceController::class, 'create']);
    Route::put('/server-devices/{id}', [ServerDeviceController::class, 'update']);
    Route::delete('/server-devices/{id}', [ServerDeviceController::class, 'delete']);
    
    Route::get('/nas-devices', [NasDeviceController::class, 'get']);
    Route::post('/nas-devices', [NasDeviceController::class, 'create']);
    Route::put('/nas-devices/{id}', [NasDeviceController::class, 'update']);
    Route::delete('/nas-devices/{id}', [NasDeviceController::class, 'delete']);

    Route::get('/network-devices', [NetworkDeviceController::class, 'get']);
    Route::post('/network-devices', [NetworkDeviceController::class, 'create']);
    Route::put('/network-devices/{id}', [NetworkDeviceController::class, 'update']);
    Route::delete('/network-devices/{id}', [NetworkDeviceController::class, 'delete']);

    // Route::get('/infrastructure', [InfrastructureDeviceController::class, 'get']);
    // Route::post('/infrastructure', [InfrastructureDeviceController::class, 'create']);
    // Route::put('/infrastructure/{id}', [InfrastructureDeviceController::class, 'update']);
    // Route::delete('/infrastructure/{id}', [InfrastructureDeviceController::class, 'delete']);
});