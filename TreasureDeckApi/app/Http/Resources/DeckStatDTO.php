<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="DeckStatDTO",
 *     title="DeckStat",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="deck_id", type="integer"),
 *     @OA\Property(property="wins", type="integer"),
 *     @OA\Property(property="losses", type="integer"),
 *     @OA\Property(property="dice", type="boolean")
 * )
 */
class DeckStatDTO extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'      => $this->id,
            'deck_id' => $this->deck_id,
            'wins'    => $this->wins,
            'losses'  => $this->losses,
            'dice' => $this->dice
        ];
    }
}
