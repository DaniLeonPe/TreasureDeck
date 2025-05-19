<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="CardVersionDTO",
 *     title="CardVersion",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="card_id", type="integer"),
 *     @OA\Property(property="versions", type="string"),
 *     @OA\Property(property="image_url", type="string"),
 *     @OA\Property(property="min_price", type="number", format="float"),
 *     @OA\Property(property="avg_price", type="number", format="float")
 * )
 */
class CardVersionDTO extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'card_id'   => $this->card_id,
            'versions'  => $this->versions,
            'image_url' => $this->image_url,
            'min_price' => $this->min_price,
            'avg_price' => $this->avg_price,
        ];
    }
}
