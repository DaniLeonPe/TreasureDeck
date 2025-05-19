<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserDTO",
 *     title="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="role_id", type="integer"),
 *     @OA\Property(property="email_verified_at", type="boolean"),
 *     @OA\Property(property="email_verification_token", type="string")
 * )
 */
class UserDTO extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                         => $this->id,
            'name'                       => $this->name,
            'email'                      => $this->email,
            'role_id'                    => $this->role_id,
            'email_verified_at'             => $this->email_verified_at,
            'email_verification_token'   => $this->email_verification_token,
        ];
    }
}
