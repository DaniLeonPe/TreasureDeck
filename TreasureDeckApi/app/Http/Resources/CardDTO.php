<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CardDTO",
 *     title="Card",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="collector_number", type="string"),
 *     @OA\Property(property="rarity", type="string"),
 *     @OA\Property(property="expansion_id", type="integer")
 * )
 */
class CardDTO extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'collector_number' => $this->collector_number,
            'rarity' => $this->rarity,
            'expansion_id' => $this->expansion_id,
        ];
    }
}
