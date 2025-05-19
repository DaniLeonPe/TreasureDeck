<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Http\Resources\CardDTO;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Cards",
 *     description="Operaciones relacionadas con cartas"
 * )
 *
 */
class CardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v2/cards",
     *     tags={"Cards"},
     *     summary="Get list of cards with pagination",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of cards",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CardDTO")
     *             ),
     *             @OA\Property(property="meta", type="object"),
     *             @OA\Property(property="links", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $cards = Card::with('expansion', 'cardsVersions')->paginate($perPage);
        return CardDTO::collection($cards);
    }

    /**
     * @OA\Post(
     *     path="/api/v2/cards",
     *     tags={"Cards"},
     *     summary="Crear una nueva carta",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "collector_number", "rarity", "expansion_id"},
     *             @OA\Property(property="name", type="string", example="Black Lotus"),
     *             @OA\Property(property="collector_number", type="string", example="001"),
     *             @OA\Property(property="rarity", type="string", example="Mythic"),
     *             @OA\Property(property="expansion_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Carta creada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/CardDTO")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de entrada inválidos"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'collector_number' => 'required|string',
            'rarity' => 'required|string',
            'expansion_id' => 'required|exists:expansions,id',
        ]);

        $card = Card::create($validated);
        return new CardDTO($card);
    }

    /**
     * @OA\Get(
     *     path="/api/v2/cards/{id}",
     *     tags={"Cards"},
     *     summary="Obtener una carta por ID",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la carta",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalle de la carta",
     *         @OA\JsonContent(ref="#/components/schemas/CardDTO")
     *     ),
     *     @OA\Response(response=404, description="Carta no encontrada")
     * )
     */
    public function show($id)
    {
        $card = Card::findOrFail($id);
        return new CardDTO($card);
    }

    /**
     * @OA\Put(
     *     path="/api/v2/cards/{id}",
     *     tags={"Cards"},
     *     summary="Actualizar una carta por ID",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la carta",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Black Lotus"),
     *             @OA\Property(property="collector_number", type="string", example="001"),
     *             @OA\Property(property="rarity", type="string", example="Mythic"),
     *             @OA\Property(property="expansion_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Carta actualizada exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/CardDTO")
     *     ),
     *     @OA\Response(response=404, description="Carta no encontrada")
     * )
     */
    public function update(Request $request, $id)
    {
        $card = Card::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'collector_number' => 'sometimes|required|string',
            'rarity' => 'sometimes|required|string',
            'expansion_id' => 'sometimes|required|exists:expansions,id',
        ]);

        $card->update($validated);

        return new CardDTO($card);
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/cards/{id}",
     *     tags={"Cards"},
     *     summary="Eliminar una carta por ID",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la carta",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Carta eliminada exitosamente"
     *     ),
     *     @OA\Response(response=404, description="Carta no encontrada")
     * )
     */
    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        $card->delete();

        return response()->noContent();
    }
}
