<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserDTO;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="users",
 *     description="Operaciones relacionadas con los usuarios"
 * )

 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v2/users",
     *     tags={"users"},
     *     summary="Listar todos los usuarios",
     * security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios"
     *     )
     * )
     */
    public function index() {
        return UserDTO::collection(User::all());
    }

    /**
     * @OA\Post(
     *     path="/api/v2/users",
     *     tags={"users"},
     *     summary="Crear un nuevo usuario",
     * security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "role_id"},
     *             @OA\Property(property="name", type="string", example="Juan Pérez"),
     *             @OA\Property(property="email", type="string", example="juan@example.com"),
     *             @OA\Property(property="password", type="string", example="secreto123"),
     *             @OA\Property(property="role_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado exitosamente"
     *     )
     * )
     */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:role,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'email_verified_at' => now()
        ]);

        return new UserDTO($user);
    }

    /**
     * @OA\Get(
     *     path="/api/v2/users/{id}",
     *     tags={"users"},
     *     summary="Obtener un usuario por ID",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del usuario"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function show($id) {
        $user = User::findOrFail($id);
        return new UserDTO($user);
    }

    /**
     * @OA\Put(
     *     path="/api/v2/users/{id}",
     *     tags={"users"},
     *     summary="Actualizar un usuario existente",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Nombre actualizado"),
     *             @OA\Property(property="email", type="string", example="nuevo@example.com"),
     *             @OA\Property(property="password", type="string", example="nuevoclave123"),
     *             @OA\Property(property="role_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado"
     *     )
     * )
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6',
            'role_id' => 'sometimes|exists:role,id',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return new UserDTO($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/users/{id}",
     *     tags={"users"},
     *     summary="Eliminar un usuario",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Usuario eliminado"
     *     )
     * )
     */
    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->noContent();
    }
}
