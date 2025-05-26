<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="DeckDTO",
 *     title="Deck",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="wins", type="integer"),
 *     @OA\Property(property="losses", type="integer"),
 *     @OA\Property(property="name", type="string")
 * )
 */
class DeckDTO extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'user_id'                => $this->user_id,
            'name'                   => $this->name,
            'wins' =>$this->wins,
            'losses' =>$this->losses,
        ];
    }
}
