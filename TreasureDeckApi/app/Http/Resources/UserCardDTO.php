<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserCardDTO",
 *     title="UserCard",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="card_version_id", type="integer"),
 *     @OA\Property(property="quantity", type="integer")
 * )
 */
class UserCardDTO extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'user_id'          => $this->user_id,
            'card_version_id'  => $this->card_version_id,
            'quantity'         => $this->quantity,
        ];
    }
}
