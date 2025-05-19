<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleDTO;
use App\Models\Role;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="role",
 *     description="Operaciones relacionadas con los role de usuario"
 * )
 */
class RoleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v2/role",
     *     tags={"role"},
     *     summary="Listar todos los role",
     * security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de role"
     *     )
     * )
     */
    public function index() {
        return RoleDTO::collection(Role::all());
    }

    /**
     * @OA\Post(
     *     path="/api/v2/role",
     *     tags={"role"},
     *     summary="Crear un nuevo rol",
     * security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Administrador")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rol creado con éxito"
     *     )
     * )
     */
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|unique:role,name']);
        return new RoleDTO(Role::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="/api/v2/role/{id}",
     *     tags={"role"},
     *     summary="Obtener un rol por ID",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del rol"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
     *     )
     * )
     */
    public function show($id) {
        $role = Role::findOrFail($id);
        return new RoleDTO($role);
    }

    /**
     * @OA\Put(
     *     path="/api/v2/role/{id}",
     *     tags={"role"},
     *     summary="Actualizar un rol existente",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Editor")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol actualizado"
     *     )
     * )
     */
    public function update(Request $request, $id) {
        $role = Role::findOrFail($id);
        $role->update($request->all());
        return new RoleDTO($role);
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/role/{id}",
     *     tags={"role"},
     *     summary="Eliminar un rol",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Rol eliminado con éxito"
     *     )
     * )
     */
    public function destroy( $id) {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->noContent();
    }
}
