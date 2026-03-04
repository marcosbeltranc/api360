<?php

namespace App\Http\Controllers;

use App\Models\OptionList;
use Illuminate\Http\Request;

class OptionListController extends Controller
{
    /**
     * GET: Obtener todas las opciones (o filtrar por tipo)
     * Acceso: Niveles 0, 1 y 2
     */
    public function get(Request $request)
    {
        $query = OptionList::where('is_active', true);

        // Permitimos filtrar por tipo si se envía en la URL: ?type=location
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        return $query->orderBy('type')->orderBy('sort_order')->get();
    }

    /**
     * CREATE: Crear una nueva opción
     * Acceso: Solo Niveles 0 y 1
     */
    public function create(Request $request)
    {
        // Bloqueo para nivel 2 (Usuario normal)
        if ($request->user()->level > 1) {
            return response()->json(['message' => 'No tienes permisos para crear registros'], 403);
        }

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|string|max:100',
            'slug'       => 'required|string|unique:option_lists,slug',
            'color'      => 'nullable|string',
            'sort_order' => 'integer',
            'option_group_id' => 'integer'
        ]);

        $option = OptionList::create($validated);
        return response()->json($option, 201);
    }

    /**
     * UPDATE: Actualizar una opción existente
     * Acceso: Solo Niveles 0 y 1
     */
    public function update(Request $request, $id)
    {
        // Bloqueo para nivel 2
        if ($request->user()->level > 1) {
            return response()->json(['message' => 'No tienes permisos para editar registros'], 403);
        }

        $option = OptionList::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'sometimes|string',
            'is_active'  => 'boolean',
            'color'      => 'nullable|string',
            'sort_order' => 'integer',
            'option_group_id' => 'integer'
        ]);

        $option->update($validated);
        return response()->json($option);
    }

    /**
     * DELETE: Eliminar un registro
     * Acceso: ÚNICAMENTE Nivel 0 (Admin)
     */
    public function delete(Request $request, $id)
    {
        // Bloqueo para niveles 1 y 2
        if ($request->user()->level !== 0) {
            return response()->json(['message' => 'Acción denegada. Solo administradores pueden eliminar'], 403);
        }

        $option = OptionList::findOrFail($id);
        $option->delete(); // Usa SoftDeletes si lo configuraste en la migración

        return response()->json(['message' => 'Registro eliminado correctamente']);
    }
}