<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeckDTO;
use App\Models\Deck;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="decks",
 *     description="Operaciones relacionadas con los mazos"
 * )
 */
class DeckController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v2/decks",
     *     tags={"decks"},
     *     summary="Obtener todos los mazos",
     * security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de mazos"
     *     )
     * )
     */
    public function index() {
        return DeckDTO::collection(Deck::all());
    }

    /**
     * @OA\Post(
     *     path="/api/v2/decks",
     *     tags={"decks"},
     *     summary="Crear un nuevo mazo",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "leader_card_version_id", "name"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="leader_card_version_id", type="integer", example=5),
     *             @OA\Property(property="name", type="string", example="Mazo Dragón")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Mazo creado con éxito"
     *     )
     * )
     */
    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'leader_card_version_id' => 'required|exists:cards_versions,id',
            'name' => 'required|string'
        ]);
        return new DeckDTO(Deck::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="/api/v2/decks/{id}",
     *     tags={"decks"},
     *     summary="Mostrar un mazo específico",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del mazo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Información del mazo"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Mazo no encontrado"
     *     )
     * )
     */
    public function show($id) {
        $deck = Deck::findOrFail($id);
        return new DeckDTO($deck);
    }

    /**
     * @OA\Put(
     *     path="/api/v2/decks/{id}",
     *     tags={"decks"},
     *     summary="Actualizar un mazo existente",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del mazo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Mazo actualizado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mazo actualizado correctamente"
     *     )
     * )
     */
    public function update(Request $request,$id) {
        $deck = Deck::findOrFail($id);
         $validated = $request->validate([
            'leader_card_version_id' => 'sometimes|exists:cards_versions,id',
            'name' => 'sometimes|string'
        ]);
        $deck->update($validated);
        return new DeckDTO($deck);
    }
   

    /**
     * @OA\Delete(
     *     path="/api/v2/decks/{id}",
     *     tags={"decks"},
     *     summary="Eliminar un mazo",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del mazo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Mazo eliminado"
     *     )
     * )
     */
    public function destroy($id) {
        $deck = Deck::findOrFail($id);
        $deck->delete();
        return response()->noContent();
    }
}
