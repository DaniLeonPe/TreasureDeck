<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardVersionDTO;
use App\Models\CardsVersion;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="cardsVersions",
 *     description="Operaciones relacionadas con las versiones de las cartas"
 * )
 */
class CardVersionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v2/cardsVersion",
     *     tags={"cardsVersions"},
     *     summary="Obtener todas las versiones de las cartas",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de versiones de cartas",
     *     )
     * )
     */
  public function index(Request $request)
{
    $limit = $request->query('limit', 20);
    $nameFilter = $request->query('name');
    $ids = $request->query('ids'); // leer el parámetro ids

    $query = CardsVersion::query();

    if ($ids) {
        $idsArray = explode(',', $ids);
        $query->whereIn('id', $idsArray);
    }

    if ($nameFilter) {
        $query->whereHas('card', function ($q) use ($nameFilter) {
            $q->where('name', 'like', "%$nameFilter%");
        });
    }

    $cards = $query->orderBy('id')->paginate($limit);

    return CardVersionDTO::collection($cards);
}









    /**
     * @OA\Post(
     *     path="/api/v2/cardsVersion",
     *     tags={"cardsVersions"},
     *     summary="Crear una nueva versión de la carta",
     * security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"card_id", "image_url", "min_price", "avg_price"},
     *             @OA\Property(property="card_id", type="integer", example=1),
     *             @OA\Property(property="image_url", type="string", format="url", example="https://example.com/card.png"),
     *             @OA\Property(property="min_price", type="number", format="float", example=1.5),
     *             @OA\Property(property="avg_price", type="number", format="float", example=2.3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Versión creada con éxito",
     *     )
     * )
     */
    public function store(Request $request) {
        $request->validate([
            'card_id' => 'required|exists:cards,id',
            'image_url' => 'required|url',
            'min_price' => 'required|numeric',
            'avg_price' => 'required|numeric',
        ]);
        return new CardVersionDTO(CardsVersion::create($request->all()));
    }

     /**
     * @OA\Get(
     *     path="/api/v2/cardsVersion/{id}",
     *     tags={"cardsVersions"},
     *     summary="Obtener una versión por ID",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la versión",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Versión encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Versión no encontrada"
     *     )
     * )
     */
    public function show($id) {
        $cardVersion = CardsVersion::findOrFail($id);
        return new CardVersionDTO($cardVersion);
    }

    /**
     * @OA\Put(
     *     path="/api/v2/cardsVersion/{id}",
     *     tags={"cardsVersions"},
     *     summary="Actualizar una versión de carta",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la versión",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="image_url", type="string", format="url", example="https://example.com/card.png"),
     *             @OA\Property(property="min_price", type="number", format="float", example=1.2),
     *             @OA\Property(property="avg_price", type="number", format="float", example=2.5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Versión actualizada"
     *     )
     * )
     */
    public function update(Request $request, $id) {
        $cardVersion = CardsVersion::findOrFail($id);
    $data = $request->validate([
        'image_url' => 'sometimes|url',
        'min_price' => 'sometimes|numeric',
        'avg_price' => 'sometimes|numeric',
        'versions' => 'sometimes|string',  
    ]);
    $updated = $cardVersion->update($data);



    return new CardVersionDTO($cardVersion);
}


    /**
     * @OA\Delete(
     *     path="/api/v2/cardsVersion/{id}",
     *     tags={"cardsVersions"},
     *     summary="Eliminar una versión de carta",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la versión",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Versión eliminada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Versión no encontrada"
     *     )
     * )
     */
    public function destroy($id) {
        $cardVersion = CardsVersion::findOrFail($id);
        $cardVersion->delete();
        return response()->noContent();
    }
}
