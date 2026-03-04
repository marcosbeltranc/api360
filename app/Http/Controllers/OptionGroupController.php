<?php

namespace App\Http\Controllers;

use App\Models\OptionGroup;
use Illuminate\Http\Request;

class OptionGroupController extends Controller
{
    /**
     * Obtiene todos los grupos con sus opciones ligadas
     */
public function get(Request $request)
{
    $groups = OptionGroup::with(['options' => function($query) {
        $query->where('is_active', true)->orderBy('sort_order', 'asc');
    }])
    ->orderBy('id', 'asc')
    ->get();

    return response()->json([
        'count' => $groups->count(),
        'data'  => $groups
    ]);
}

    /**
     * Crea un nuevo grupo (Categoría)
     */
    public function create(Request $request)
    {
        // Mantenemos tu lógica de seguridad por niveles
        if ($request->user()->level > 1) {
            return response()->json(['message' => 'No tiene permisos para crear'], 403);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|unique:option_groups,code',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string',
        ]);

        $group = OptionGroup::create($request->all());

        return response()->json($group, 201);
    }

    /**
     * Actualiza el grupo (ideal para cambiar la descripción o el nombre)
     */
    public function update(Request $request, $id)
    {
        if ($request->user()->level > 1) {
            return response()->json(['message' => 'No tiene permisos para editar'], 403);
        }

        $group = OptionGroup::findOrFail($id);
        
        $request->validate([
            'name' => 'string|max:255',
            'code' => 'string|unique:option_groups,code,' . $id,
        ]);

        $group->update($request->all());

        return response()->json($group);
    }

    /**
     * Elimina un grupo
     */
    public function delete(Request $request, $id)
    {
        if ($request->user()->level !== 0) {
            return response()->json(['message' => 'Solo administradores nivel 0 pueden eliminar'], 403);
        }

        $group = OptionGroup::findOrFail($id);
        
        $group->delete();

        return response()->json(['message' => 'Grupo eliminado correctamente']);
    }
}