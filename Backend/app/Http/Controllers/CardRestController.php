<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardDTO;
use App\Models\Card;
use Illuminate\Http\Request;

class CardRestController extends Controller
{
    /**
* @OA\Get(
* path="/api/cards",
* summary="Obtener lista de cartas",
* description="Retorna una lista de cartas",
* tags={"Cards"},
* @OA\Response(
* response=200,
* description="Lista de cartas"
* )
* )
*/
    public function index()
    {
        return response()->json([
            'foo' =>'bar',
            ]);    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $card = Card::create([
            'name' => $request->nombre,
            'collector_number' => $request->collector_number,
            'rarity' => $request->rarity,
            'expansion_id' => $request->expansion_id,

        ]);
        return new CardDTO($card);
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        $card->delete();
        return response()->json(null, 204);
    }
}
