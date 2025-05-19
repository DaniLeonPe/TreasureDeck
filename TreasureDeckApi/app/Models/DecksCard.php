<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $deck_id
 * @property integer $card_version_id
 * @property boolean $quantity
 * @property CardsVersion $cardsVersion
 * @property Deck $deck
 */
class DecksCard extends Model
{
    /**
     * @var array
     */
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['deck_id', 'card_version_id', 'quantity'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cardsVersion()
    {
        return $this->belongsTo('App\Models\CardsVersion', 'card_version_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deck()
    {
        return $this->belongsTo('App\Models\Deck');
    }
}
