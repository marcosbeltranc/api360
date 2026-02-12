<?php

namespace App\Http\Controllers;

use App\Models\InfrastructureDevice;
use Illuminate\Http\Request;

class InfrastructureDeviceController extends Controller
{
    /**
     * GET: Listar dispositivos con filtros
     */
    public function get(Request $request)
    {
        $query = InfrastructureDevice::with(['deviceType', 'subType', 'location', 'status']);

        // Filtrar por device_type (slug) si se solicita (ej: ?device_type=server)
        if ($request->filled('device_type')) {
            $query->whereHas('deviceType', function($q) use ($request) {
                $q->where('slug', $request->query('device_type'));
            });
        }

        return $query->latest()->get();
    }

    /**
     * CREATE: Guardar nuevo dispositivo (Nivel 0 y 1)
     */
    public function create(Request $request)
    {
        if ($request->user()->level > 1) {
            return response()->json(['message' => 'No tiene permisos para crear'], 403);
        }

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'device_type_id' => 'required|exists:option_lists,id',
            'location_id'    => 'required|exists:option_lists,id',
            'status_id'      => 'required|exists:option_lists,id',
            'sub_type_id'    => 'nullable|exists:option_lists,id',
            'ip_address'     => 'nullable|ip',
            // Los demás campos son opcionales (nullable en la migración)
        ]);

        $device = InfrastructureDevice::create($request->all());
        return response()->json($device->load(['deviceType', 'subType']), 201);
    }

    /**
     * UPDATE: Editar dispositivo (Nivel 0 y 1)
     */
    public function update(Request $request, $id)
    {
        if ($request->user()->level > 1) {
            return response()->json(['message' => 'No tiene permisos para editar'], 403);
        }

        $device = InfrastructureDevice::findOrFail($id);
        $device->update($request->all());

        return response()->json($device->load(['deviceType', 'subType', 'location', 'status']));
    }

    /**
     * DELETE: Borrado lógico (Solo Nivel 0)
     */
    public function delete(Request $request, $id)
    {
        if ($request->user()->level !== 0) {
            return response()->json(['message' => 'Solo administradores pueden eliminar'], 403);
        }

        $device = InfrastructureDevice::findOrFail($id);
        $device->delete();

        return response()->json(['message' => 'Dispositivo eliminado correctamente (Soft Delete)']);
    }
}