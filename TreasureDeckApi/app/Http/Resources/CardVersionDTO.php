<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CardVersionDTO extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'card_id' => $this->card_id,
            'versions' => $this->versions,
            'image_url' => $this->image_url,
            'min_price' => $this->min_price,
            'avg_price' => $this->avg_price,
        ];
    }
}
