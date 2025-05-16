<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $card_id
 * @property string $versions
 * @property string $image_url
 * @property float $min_price
 * @property float $avg_price
 * @property UserCard[] $userCards
 * @property DecksCard[] $decksCards
 * @property Deck[] $decks
 * @property Card $card
 */
class CardVersion extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'cards_versions';

    /**
     * @var array
     */
    protected $fillable = ['card_id', 'versions', 'image_url', 'min_price', 'avg_price'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCards()
    {
        return $this->hasMany('App\Models\UserCard', 'card_version_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function decksCards()
    {
        return $this->hasMany('App\Models\DecksCard', 'card_version_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function decks()
    {
        return $this->hasMany('App\Models\Deck', 'leader_card_version_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function card()
    {
        return $this->belongsTo('App\Models\Card');
    }
}
