<?php

namespace App\Http\Controllers;

use App\Models\DeviceMaintenance;
use Illuminate\Http\Request;

class DeviceMaintenanceController extends Controller
{
    /**
     * Obtener mantenimientos filtrados por tipo de dispositivo e ID
     * Ejemplo: /api/maintenance?device_id=1&device_type=App\Models\NasDevice
     */
    public function get(Request $request)
    {
        $query = DeviceMaintenance::with(['maintenanceType', 'responsible', 'device']);

        if ($request->filled('device_id') && $request->filled('device_type')) {
            $query->where('device_id', $request->device_id)
                  ->where('device_type', $request->device_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest('completion_date')->get();

        return response()->json([
            'count' => $logs->count(),
            'data'  => $logs
        ]);
    }

    public function create(Request $request)
    {
        // Validación de nivel de usuario (siguiendo tu lógica de nivel > 1)
        if ($request->user()->level > 1) {
            return response()->json(['message' => 'No tiene permisos para registrar mantenimientos'], 403);
        }

        $request->validate([
            'device_id'           => 'required|integer',
            'device_type'         => 'required|string', // Ej: "App\Models\ServerDevice"
            'maintenance_type_id' => 'required|exists:option_lists,id',
            'title'               => 'required|string|max:255',
            'responsible_id'      => 'required|exists:users,id',
            'completion_date'     => 'required|date',
            'details'             => 'nullable|string',
            'validation_checklist'=> 'nullable|array'
        ]);

        $maintenance = DeviceMaintenance::create($request->all());

        return response()->json($maintenance->load(['maintenanceType', 'responsible']), 201);
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->level > 1) {
            return response()->json(['message' => 'No tiene permisos para editar'], 403);
        }

        $maintenance = DeviceMaintenance::findOrFail($id);
        $maintenance->update($request->all());

        return response()->json($maintenance->load(['maintenanceType', 'responsible']));
    }

    public function delete(Request $request, $id)
    {
        if ($request->user()->level !== 0) {
            return response()->json(['message' => 'Solo administradores pueden eliminar registros históricos'], 403);
        }

        $maintenance = DeviceMaintenance::findOrFail($id);
        $maintenance->delete();

        return response()->json(['message' => 'Registro de mantenimiento eliminado correctamente']);
    }
}