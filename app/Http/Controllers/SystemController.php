<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SystemController extends Controller
{
    // Obtener todos los sistemas con sus diccionarios
    public function get(Request $request)
    {
        $systems = System::with(['server', 'status', 'priority', 'area', 'responsible'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'count' => $systems->count(),
            'data'  => $systems
        ]);
    }

    public function getById(Request $request, $id)
    {
        $system = System::with(['server', 'status', 'priority', 'area', 'responsible'])->findOrFail($id);
        return response()->json($system);
    }
    // Crear un nuevo sistema
    public function create(Request $request)
    {
        // Validamos que al menos tenga nombre y servidor
        $request->validate([
            'name' => 'required|string',
            'server_device_id' => 'required|integer',
        ]);

        $system = System::create($request->all());

        return response()->json([
            'message' => 'Sistema registrado correctamente',
            'data' => $system->load(['server', 'status', 'priority', 'area', 'responsible'])
        ]);
    }

    // Actualizar sistema existente
    public function update(Request $request, $id)
    {
        $system = System::findOrFail($id);
        
        // Si quieres que el last_update se refresque automáticamente al editar
        $data = $request->all();
        $data['last_update'] = Carbon::now();

        $system->update($data);

        return response()->json([
            'message' => 'Cambios guardados',
            'data' => $system->load(['server', 'status', 'priority', 'area', 'responsible'])
        ]);
    }

    // Eliminar (Soft Delete)
    public function delete($id)
    {
        $system = System::findOrFail($id);
        $system->delete();

        return response()->json([
            'message' => 'Sistema eliminado de la lista activa'
        ]);
    }
}