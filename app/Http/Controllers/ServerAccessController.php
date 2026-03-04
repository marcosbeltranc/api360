<?php

namespace App\Http\Controllers;

use App\Models\ServerAccess;
use Illuminate\Http\Request;

class ServerAccessController extends Controller
{
    public function get(Request $request)
    {
        $query = ServerAccess::with(['server']);

        if ($request->filled('server_id')) {
            $query->where('server_id', $request->server_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('access_ip', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $accesses = $query->latest()->get();
        
        return response()->json([
            'count' => $accesses->count(),
            'data'  => $accesses
        ]);
    }

    public function getById($id)
    {
        $access = ServerAccess::with(['server'])->findOrFail($id);
        return response()->json($access);
    }

    public function create(Request $request)
    {
        if ($request->user()->level > 1) {
            return response()->json(['message' => 'No tiene permisos para crear métodos de acceso'], 403);
        }

        $request->validate([
            'server_device_id' => 'required|exists:server_devices,id',
            'name'      => 'required|string|max:255',
            'access_ip' => 'nullable|string',
            'port'      => 'nullable|integer',
        ]);

        $access = ServerAccess::create($request->all());

        return response()->json($access->load('server'), 201);
    }

    public function update(Request $request, $id)
    {
        if ($request->user()->level > 1) {
            return response()->json(['message' => 'No tiene permisos para editar'], 403);
        }

        $access = ServerAccess::findOrFail($id);
        $access->update($request->all());

        return response()->json($access->load('server'));
    }

    public function delete(Request $request, $id)
    {
        if ($request->user()->level !== 0) {
            return response()->json(['message' => 'Solo administradores pueden eliminar'], 403);
        }

        $access = ServerAccess::findOrFail($id);
        $access->delete();

        return response()->json(['message' => 'Método de acceso eliminado correctamente']);
    }
}