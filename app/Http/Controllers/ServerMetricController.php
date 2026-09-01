<?php

use App\Events\ServerMetricsUpdated;
use App\Models\ServerMetric;
use Illuminate\Http\Request;

Route::post('/metrics', function (Request $request) {
    $payload = $request->all();

    // Si el payload viene envuelto en un evento de WebSockets tipo {"event": "...", "data": "{...}"}
    $rawMetrics = $payload['data'] ?? $payload;

    if (is_string($rawMetrics)) {
        $rawMetrics = json_decode($rawMetrics, true);
    }

    // Asegurarnos de extraer el hostname sin importar si está anidado en ['data']['hostname'] o directo
    $metricsData = $rawMetrics['data'] ?? $rawMetrics;
    $hostname = $metricsData['hostname'] ?? null;

    if (!$hostname) {
        return response()->json(['ok' => false, 'error' => 'Hostname not found'], 422);
    }

    $metric = ServerMetric::create([
        'name' => $hostname,
        'stats' => $metricsData,
    ]);

    event(new ServerMetricsUpdated($metricsData));

    return response()->json(['ok' => true]);
});