<?php

namespace App\Http\Controllers;

use App\Models\NasDevice;
use Illuminate\Http\Request;

class NasDeviceController extends Controller
{
    public function get(Request $request)
    {
        $query = NasDevice::with(['status', 'deviceType', 'location', 'responsible']);

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

        $device = NasDevice::create($request->all());
        return response()->json($device->load(['status', 'deviceType', 'location', 'responsible']), 201);
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->level > 1) {
            return response()->json(['message' => 'No tiene permisos para editar'], 403);
        }

        $device = NasDevice::findOrFail($id);
        $device->update($request->all());

        return response()->json($device->load(['status', 'deviceType', 'location', 'responsible']));
    }

    public function delete(Request $request, $id)
    {
        if ($request->user()->level !== 0) {
            return response()->json(['message' => 'Solo administradores pueden eliminar'], 403);
        }

        $device = NasDevice::findOrFail($id);
        $device->delete();

        return response()->json(['message' => 'NAS eliminado correctamente (Soft Delete)']);
    }
}