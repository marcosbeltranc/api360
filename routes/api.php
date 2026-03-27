<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OptionListController;
use App\Http\Controllers\OptionGroupController;

use App\Http\Controllers\ServerDeviceController;
use App\Http\Controllers\NasDeviceController;
use App\Http\Controllers\NetworkDeviceController;
use App\Http\Controllers\ServerAccessController;
use App\Http\Controllers\ServerUsersController;
use App\Models\ServerMetric;
use App\Events\ServerMetricsUpdated;
use App\Http\Controllers\ServerAccessRequestController;

use App\Http\Controllers\SystemController;
use App\Http\Controllers\SystemFaqController;
use App\Http\Controllers\SystemFileController;

use App\Http\Controllers\DeviceMaintenanceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public
Route::post('/login', [AuthController::class, 'login']);

Route::post('/metrics', function (Request $request) {

    $data = $request->all();

    $lastMetric = ServerMetric::where('name', $data['hostname'])
        ->latest()
        ->first();

    $currentHasAlerts = !empty($data['alerts']);
    $lastHadAlerts = $lastMetric && !empty($lastMetric->stats['alerts'] ?? []);

    if ($currentHasAlerts) {

        // Siempre insert
        ServerMetric::create([
            'name' => $data['hostname'],
            'stats' => $data,
        ]);

    } else {

        if (!$lastMetric) {

            // Primer registro
            ServerMetric::create([
                'name' => $data['hostname'],
                'stats' => $data,
            ]);

        } else if ($lastHadAlerts) {

            // recovery (alert → fine)
            ServerMetric::create([
                'name' => $data['hostname'],
                'stats' => $data,
            ]);

        } else {

            // revisar el anterior
            $previousMetric = ServerMetric::where('name', $data['hostname'])
                ->latest()
                ->skip(1)
                ->first();

            $previousHadAlerts = $previousMetric && !empty($previousMetric->stats['alerts'] ?? []);

            if ($previousHadAlerts) {

                // transición importante (fin de alerta)
                ServerMetric::create([
                    'name' => $data['hostname'],
                    'stats' => $data,
                ]);

            } else {

                // update normal (estado estable)
                $lastMetric->update([
                    'stats' => $data,
                ]);
            }
        }
    }

    // evento siempre se dispara
    event(new ServerMetricsUpdated($data));

    return response()->json(['success' => true]);
});

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn (Request $request) => $request->user());

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'get']);
        Route::post('/', [UserController::class, 'create']);
        Route::get('/responsibles', [UserController::class, 'getResponsibles']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'delete']);
    });

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    */
    Route::prefix('options')->group(function () {
        Route::get('/', [OptionListController::class, 'get']);
        Route::post('/', [OptionListController::class, 'create']);
        Route::put('/{id}', [OptionListController::class, 'update']);
        Route::delete('/{id}', [OptionListController::class, 'delete']);
    });

    Route::prefix('option-groups')->group(function () {
        Route::get('/', [OptionGroupController::class, 'get']);
        Route::post('/', [OptionGroupController::class, 'create']);
        Route::put('/{id}', [OptionGroupController::class, 'update']);
        Route::delete('/{id}', [OptionGroupController::class, 'delete']);
    });

    /*
    |--------------------------------------------------------------------------
    | Devices
    |--------------------------------------------------------------------------
    */
    Route::prefix('server-devices')->group(function () {
        Route::get('/', [ServerDeviceController::class, 'get']);
        Route::get('/{id}', [ServerDeviceController::class, 'getById']);
        Route::post('/', [ServerDeviceController::class, 'create']);
        Route::put('/{id}', [ServerDeviceController::class, 'update']);
        Route::delete('/{id}', [ServerDeviceController::class, 'delete']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/servers/{server}/access-request', [ServerAccessRequestController::class, 'store']);
    });

    Route::get('/devices', function () {

        return \App\Models\ServerMetric::select('name', 'stats', 'created_at')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('device_metrics')
                    ->groupBy('name');
            })
            ->get();
    });

    Route::prefix('nas-devices')->group(function () {
        Route::get('/', [NasDeviceController::class, 'get']);
        Route::get('/{id}', [NasDeviceController::class, 'getById']);
        Route::post('/', [NasDeviceController::class, 'create']);
        Route::put('/{id}', [NasDeviceController::class, 'update']);
        Route::delete('/{id}', [NasDeviceController::class, 'delete']);
    });

    Route::prefix('network-devices')->group(function () {
        Route::get('/', [NetworkDeviceController::class, 'get']);
        Route::post('/', [NetworkDeviceController::class, 'create']);
        Route::put('/{id}', [NetworkDeviceController::class, 'update']);
        Route::delete('/{id}', [NetworkDeviceController::class, 'delete']);
    });

    /*
    |--------------------------------------------------------------------------
    | Access & Users (Servers)
    |--------------------------------------------------------------------------
    */
    Route::prefix('server-access')->group(function () {
        Route::get('/', [ServerAccessController::class, 'get']);
        Route::post('/', [ServerAccessController::class, 'create']);
        Route::put('/{id}', [ServerAccessController::class, 'update']);
        Route::delete('/{id}', [ServerAccessController::class, 'delete']);
    });

    Route::prefix('server-users')->group(function () {
        Route::get('/', [ServerUsersController::class, 'get']);
        Route::post('/', [ServerUsersController::class, 'create']);
        Route::put('/{id}', [ServerUsersController::class, 'update']);
        Route::delete('/{id}', [ServerUsersController::class, 'delete']);
    });

    /*
    |--------------------------------------------------------------------------
    | Systems
    |--------------------------------------------------------------------------
    */
    Route::prefix('systems')->group(function () {
        Route::get('/', [SystemController::class, 'get']);
        Route::get('/{id}', [SystemController::class, 'getById']);
        Route::post('/', [SystemController::class, 'create']);
        Route::put('/{id}', [SystemController::class, 'update']);
        Route::delete('/{id}', [SystemController::class, 'delete']);
    });

    Route::prefix('files')->group(function () {
        Route::get('/', [SystemFileController::class, 'get']);
        Route::post('/', [SystemFileController::class, 'upload']);
        Route::get('/{id}/download', [SystemFileController::class, 'download']);
        Route::get('/{id}/preview', [SystemFileController::class, 'preview']);
        Route::put('/{id}', [SystemFileController::class, 'update']);
        Route::delete('/{id}', [SystemFileController::class, 'delete']);
    });
    /*
    |--------------------------------------------------------------------------
    | FAQs (Knowledge Base)
    |--------------------------------------------------------------------------
    */
    Route::prefix('faqs')->group(function () {
        Route::get('/', [SystemFaqController::class, 'get']);
        Route::get('/grouped', [SystemFaqController::class, 'grouped']);
        Route::get('/{id}', [SystemFaqController::class, 'getById']);
        Route::post('/', [SystemFaqController::class, 'create']);
        Route::put('/{id}', [SystemFaqController::class, 'update']);
        Route::delete('/{id}', [SystemFaqController::class, 'delete']);
    });

    /*
    |--------------------------------------------------------------------------
    | Maintenance
    |--------------------------------------------------------------------------
    */
    Route::prefix('device-maintenances')->group(function () {
        Route::get('/', [DeviceMaintenanceController::class, 'get']);
        Route::post('/', [DeviceMaintenanceController::class, 'create']);
        Route::put('/{id}', [DeviceMaintenanceController::class, 'update']);
        Route::delete('/{id}', [DeviceMaintenanceController::class, 'delete']);
    });

});