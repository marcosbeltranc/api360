<?php

namespace App\Http\Controllers;

use App\Models\ServerDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServerDeviceController extends Controller
{
    public function get(Request $request)
    {
        $query = ServerDevice::with(['status', 'deviceType', 'location', 'responsible', 'serverType', 'serverAccess', 'serverUsers']);

        // Filtro opcional por tipo o búsqueda general
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        $devices = $query->latest()->get();
        return response()->json([
            'count' => $devices->count(),
            'data'  => $devices
        ]);
    }

    public function getById(Request $request, $id)
    {
        $device = ServerDevice::with(['status', 'deviceType', 'location', 'responsible', 'serverType', 'serverAccess', 'serverUsers'])->findOrFail($id);
        return response()->json($device);
    }

    public function create(Request $request)
    {
        if ($request->user()->level > 1) {
            return response()->json(['message' => 'No tiene permisos para crear'], 403);
        }

        $request->validate([
            'name'           => 'required|string|max:255',
            'status_id'      => 'required|exists:option_lists,id',
            'device_type_id' => 'required|exists:option_lists,id',
            'location_id'    => 'required|exists:option_lists,id',
            'responsible_id' => 'nullable|exists:users,id',
            'ip_address'     => 'nullable|ip',
        ]);

        $server = ServerDevice::create($request->all());

        return response()->json($server->load(['status', 'deviceType', 'location', 'responsible', 'serverType', 'serverAccess', 'serverUsers']), 201);
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->level > 1) {
            return response()->json(['message' => 'No tiene permisos para editar'], 403);
        }

        $server = ServerDevice::findOrFail($id);
        $server->update($request->all());

        return response()->json($server->load(['status', 'deviceType', 'location', 'responsible', 'serverType', 'serverAccess', 'serverUsers']));
    }

    public function delete(Request $request, $id)
    {
        if ($request->user()->level !== 0) {
            return response()->json(['message' => 'Solo administradores pueden eliminar'], 403);
        }

        $server = ServerDevice::findOrFail($id);
        $server->delete();

        return response()->json(['message' => 'Servidor eliminado correctamente (Soft Delete)']);
    }
}