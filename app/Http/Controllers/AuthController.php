<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Post(
 * path="/login",
 * tags={"Autenticación"},
 * summary="Iniciar sesión",
 * @OA\RequestBody(
 * @OA\JsonContent(
 * @OA\Property(property="email", type="string", example="admin@api360.com"),
 * @OA\Property(property="password", type="string", example="password"),
 * @OA\Property(property="device_name", type="string", example="api360"),
 * )
 * ),
 * @OA\Response(response=200, description="OK"),
 * @OA\Response(response=401, description="No autorizado")
 * )
 */

/**
 * @group Autenticación
 */
class AuthController extends Controller
{
    /**
     * Login
     * Genera un token persistente.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        // El token se guarda en la DB y no expira hasta que se borre
        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Logout
     * Revoca el token actual.
     * @authenticated
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }
}