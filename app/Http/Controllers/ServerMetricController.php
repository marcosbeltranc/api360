<?php

use App\Events\ServerMetricsUpdated;
use App\Models\ServerMetric;

Route::post('/metrics', function (Request $request) {

    $data = $request->all();

    $metric = ServerMetric::create([
        'name' => $data['hostname'],
        'stats' => $data,
    ]);

    event(new \App\Events\ServerMetricsUpdated($data));

    return response()->json(['ok' => true]);
});