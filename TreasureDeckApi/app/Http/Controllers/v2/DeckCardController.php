<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeckCardDTO;
use App\Models\DecksCard;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="deckCards",
 *     description="Operaciones relacionadas con las cartas dentro de mazos"
 * )
 */
class DeckCardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v2/deckCards",
     *     tags={"deckCards"},
     *     summary="Listar todas las cartas en mazos",
     * security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de cartas en mazos"
     *     )
     * )
     */
    public function index() {
        return DeckCardDTO::collection(DecksCard::all());
    }

    /**
     * @OA\Post(
     *     path="/api/v2/deckCards",
     *     tags={"deckCards"},
     *     summary="Agregar una carta a un mazo",
     * security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"deck_id", "card_version_id", "quantity"},
     *             @OA\Property(property="deck_id", type="integer", example=1),
     *             @OA\Property(property="card_version_id", type="integer", example=10),
     *             @OA\Property(property="quantity", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Carta agregada al mazo"
     *     )
     * )
     */
    public function store(Request $request) {
        $request->validate([
            'deck_id' => 'required|exists:decks,id',
            'card_version_id' => 'required|exists:cards_versions,id',
            'quantity' => 'required|integer|min:1'
        ]);
        return new DeckCardDTO(DecksCard::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="/api/v2/deckCards/{id}",
     *     tags={"deckCards"},
     *     summary="Mostrar una carta específica de un mazo",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la carta en el mazo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Carta encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Carta no encontrada"
     *     )
     * )
     */
    public function show($id) {
        $deckCard = DecksCard::findOrFail($id);
        return new DeckCardDTO($deckCard);
    }

    /**
     * @OA\Put(
     *     path="/api/v2/deckCards/{id}",
     *     tags={"deckCards"},
     *     summary="Actualizar una carta en el mazo",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la carta en el mazo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="quantity", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Carta actualizada"
     *     )
     * )
     */
    public function update(Request $request, $id) {
        $deckCard = DecksCard::findOrFail($id);
        $validated = $request->validate([
            'card_version_id' => 'sometimes|exists:cards_versions,id',
            'quantity' => 'sometimes|integer|min:1'
        ]);
        $deckCard->update($validated);
        return new DeckCardDTO($deckCard);
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/deckCards/{id}",
     *     tags={"deckCards"},
     *     summary="Eliminar una carta del mazo",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la carta en el mazo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Carta eliminada del mazo"
     *     )
     * )
     */
    public function destroy($id) {
        $deckCard = DecksCard::findOrFail($id);
        $deckCard->delete();
        return response()->noContent();
    }
}
