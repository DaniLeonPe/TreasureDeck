<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeckStatDTO;
use App\Models\DecksStat;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="deckStats",
 *     description="Operaciones relacionadas con las estadísticas de los mazos"
 * )
 */
class DeckStatController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v2/deckStats",
     *     tags={"deckStats"},
     *     summary="Listar estadísticas de todos los mazos",
     * security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de estadísticas"
     *     )
     * )
     */
    public function index() {
        return DeckStatDTO::collection(DecksStat::all());
    }

    /**
     * @OA\Post(
     *     path="/api/v2/deckStats",
     *     tags={"deckStats"},
     *     summary="Crear estadísticas para un mazo",
     * security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"deck_id", "wins", "losses"},
     *             @OA\Property(property="deck_id", type="integer", example=1),
     *             @OA\Property(property="wins", type="integer", example=10),
     *             @OA\Property(property="losses", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Estadística creada exitosamente"
     *     )
     * )
     */
    public function store(Request $request) {
        $request->validate([
            'deck_id' => 'required|exists:decks,id',
            'wins' => 'required|integer|min:0',
            'losses' => 'required|integer|min:0',
            'dice' => 'required|boolean'
        ]);
        return new DeckStatDTO(DecksStat::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="/api/v2/deckStats/{id}",
     *     tags={"deckStats"},
     *     summary="Mostrar estadísticas de un mazo por ID",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de las estadísticas del mazo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estadísticas del mazo"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No encontrado"
     *     )
     * )
     */
    public function show($id) {
        $deckStat = DecksStat::findOrFail($id);
        return new DeckStatDTO($deckStat);
    }

    /**
     * @OA\Put(
     *     path="/api/v2/deckStats/{id}",
     *     tags={"deckStats"},
     *     summary="Actualizar estadísticas de un mazo",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de las estadísticas del mazo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="wins", type="integer", example=12),
     *             @OA\Property(property="losses", type="integer", example=6)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estadísticas actualizadas"
     *     )
     * )
     */
    
    public function update(Request $request, $id) {
        $deckStat = DecksStat::findOrFail($id);
        $validated = $request->validate([
            'wins' => 'sometimes|integer|min:0',
            'losses' => 'sometimes|integer|min:0',
            'dice' => 'sometimes|boolean'
        ]);
        $deckStat->update($validated);
        return new DeckStatDTO($deckStat);
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/deckStats/{id}",
     *     tags={"deckStats"},
     *     summary="Eliminar estadísticas de un mazo",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de las estadísticas del mazo",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Estadísticas eliminadas"
     *     )
     * )
     */
    public function destroy($id) {
        $deckStat = DecksStat::findOrFail($id);
        $deckStat->delete();
        return response()->noContent();
    }
}
