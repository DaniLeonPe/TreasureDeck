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
     *     summary="Obtener todos los mazos del usuario",
     * security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de mazos"
     *     )
     * )
     */
    public function index() {
       return DeckDTO::collection(Deck::where('user_id', auth()->id())->get());

    }

    /**
     * @OA\Post(
     *     path="/api/v2/decks",
     *     tags={"decks"},
     *     summary="Crear un nuevo mazo",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "name"},
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
        $validated = $request->validate([
    'name' => 'required|string'
    
]);

$deck = new Deck($validated);
$deck->user_id = auth()->id();
$deck->wins = 0;
$deck->losses = 0;
$deck->save();

return new DeckDTO($deck);

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
        $deck = Deck::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
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
$deck = Deck::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
         $validated = $request->validate([
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
        $deck = Deck::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
$deck->delete();
return response()->noContent();

    }
}
