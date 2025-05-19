<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCardDTO;
use App\Models\UserCard;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="userCards",
 *     description="Operaciones relacionadas con las cartas de usuario"
 * )
 */
class UserCardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v2/userCards",
     *     tags={"userCards"},
     *     summary="Listar todas las cartas de usuario",
     * security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de cartas de usuario"
     *     )
     * )
     */
    public function index() {
        return UserCardDTO::collection(UserCard::all());
    }

    /**
     * @OA\Post(
     *     path="/api/v2/userCards",
     *     tags={"userCards"},
     *     summary="Crear una carta de usuario",
     * security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "card_version_id", "quantity"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="card_version_id", type="integer", example=5),
     *             @OA\Property(property="quantity", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Carta de usuario creada"
     *     )
     * )
     */
    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'card_version_id' => 'required|exists:cards_versions,id',
            'quantity' => 'required|integer|min:0'
        ]);
        return new UserCardDTO(UserCard::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="/api/v2/userCards/{id}",
     *     tags={"userCards"},
     *     summary="Obtener una carta de usuario por ID",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la carta de usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Carta de usuario encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Carta de usuario no encontrada"
     *     )
     * )
     */
    public function show($id) {
        $userCard = UserCard::findOrFail($id);
        return new UserCardDTO($userCard);
    }

    /**
     * @OA\Put(
     *     path="/api/v2/userCards/{id}",
     *     tags={"userCards"},
     *     summary="Actualizar una carta de usuario",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la carta de usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="quantity", type="integer", example=4)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Carta de usuario actualizada"
     *     )
     * )
     */
    public function update(Request $request,  $id) {
        $userCard = UserCard::findOrFail($id);
        $userCard->update($request->all());
        return new UserCardDTO($userCard);
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/userCards/{id}",
     *     tags={"userCards"},
     *     summary="Eliminar una carta de usuario",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la carta de usuario",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Carta de usuario eliminada"
     *     )
     * )
     */
    public function destroy($id) {
        $userCard = UserCard::findOrFail($id);
        $userCard->delete();
        return response()->noContent();
    }
}
