<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SystemController extends Controller
{
    public function get(Request $request)
    {
        $systems = System::with([
            'server',
            'status',
            'priority',
            'area', 
            'areas',
            'responsible'
        ])
        ->orderBy('id', 'desc')
        ->get();

        return response()->json([
            'count' => $systems->count(),
            'data'  => $systems
        ]);
    }

    public function getById(Request $request, $id)
    {
        $system = System::with([
            'server',
            'status',
            'priority',
            'area',
            'areas',
            'responsible'
        ])->findOrFail($id);

        return response()->json($system);
    }

    // Crear sistema
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'server_device_id' => 'required|integer',
        ]);

        $system = System::create($request->except('areas'));

        if ($request->has('areas')) {
            $system->areas()->sync($request->areas);
        }

        return response()->json([
            'message' => 'Sistema registrado correctamente',
            'data' => $system->load([
                'server',
                'status',
                'priority',
                'area',
                'areas',
                'responsible'
            ])
        ]);
    }

    // Actualizar sistema
    public function update(Request $request, $id)
    {
        $system = System::findOrFail($id);

        $data = $request->except('areas');
        $data['last_update'] = Carbon::now();

        $system->update($data);

        if ($request->has('areas')) {
            $system->areas()->sync($request->areas);
        }

        return response()->json([
            'message' => 'Cambios guardados',
            'data' => $system->load([
                'server',
                'status',
                'priority',
                'area',
                'areas',
                'responsible'
            ])
        ]);
    }

    // Soft delete
    public function delete($id)
    {
        $system = System::findOrFail($id);
        $system->delete();

        return response()->json([
            'message' => 'Sistema eliminado de la lista activa'
        ]);
    }
}