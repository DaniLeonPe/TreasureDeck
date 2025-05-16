<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'idkeyMostrada' => $this->id,
            'nameMostrado' =>$this->nombre,
            'collectorNumberMostrado' => $this->collector_number,
            'rarityMostrado' => $this->rarity,
            'expansionIdMostrado' => $this->expansion_id
        ];
    }
}
