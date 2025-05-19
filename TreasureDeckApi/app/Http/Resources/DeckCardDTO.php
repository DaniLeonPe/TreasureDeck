<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="DeckCardDTO",
 *     title="DeckCard",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="deck_id", type="integer"),
 *     @OA\Property(property="card_version_id", type="integer"),
 *     @OA\Property(property="quantity", type="integer")
 * )
 */

class DeckCardDTO extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
          'id' => $this->id,
            'deck_id' => $this->deck_id,
            'card_version_id' => $this->card_version_id,
            'quantity' => $this->quantity,
        ];
    }
}
