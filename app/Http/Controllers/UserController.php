<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * @group Gestión de Usuarios
 * * APIs para manejar el ciclo de vida de los usuarios en API360.
 */
class UserController extends Controller
{
    /**
     * Listar usuarios
     * * Retorna todos los usuarios que no han sido borrados lógicamente.
     * @authenticated
     */
    public function get()
    {
        return response()->json(User::all(), 200);
    }

    /**
     * Crear usuario
     * * Registra un nuevo usuario con un nivel específico.
     * @bodyParam name string required El nombre del usuario. Example: Marcos Beltran
     * @bodyParam email string required Email único. Example: marcos@example.com
     * @bodyParam password string required Mínimo 8 caracteres.
     * @bodyParam level int Nivel de acceso (0:Admin, 1:Dev, 2:User). Example: 2
     * @authenticated
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'level'    => 'required|integer|between:0,4',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'level'    => $validated['level'],
        ]);

        return response()->json([
            'message' => 'Usuario creado con éxito',
            'user'    => $user
        ], 201);
    }

    /**
     * Listar responsables
     * * Retorna todos los usuarios que no han sido borrados lógicamente.
     * @authenticated
     */
    public function getResponsibles()
    {
        $users = User::whereIn('level', [0, 1, 2, 3])
                     ->whereNull('deleted_at')
                     ->select('id', 'name', 'email')
                 ->get();

        return response()->json($users, 200);
    }

    /**
     * Ver detalle de usuario
     * * @urlParam id int required ID del usuario. Example: 1
     * @authenticated
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    /**
     * Actualizar usuario
     * * Actualiza datos específicos. Si se envía password, se encripta de nuevo.
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => [
                'sometimes', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'sometimes|string|min:8',
            'level'    => 'sometimes|integer|between:0,4',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Usuario actualizado con éxito',
            'user'    => $user
        ], 200);
    }

    /**
     * Eliminar usuario (Soft Delete)
     * * Marca al usuario como eliminado y revoca todos sus tokens de acceso inmediatamente.
     * @urlParam id int required ID del usuario a eliminar.
     * @authenticated
     */
    public function delete($id)
    {
        $user = User::findOrFail($id);

        // 1. Revocamos todos sus tokens para que su sesión muera en todos lados
        $user->tokens()->delete();

        // 2. Ejecutamos el Soft Delete (requiere el trait SoftDeletes en el Modelo User)
        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado lógicamente y sesiones cerradas con éxito'
        ], 200);
    }
}