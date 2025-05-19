<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $card_id
 * @property string $versions
 * @property string $image_url
 * @property float $min_price
 * @property float $avg_price
 * @property Card $card
 * @property \Illuminate\Database\Eloquent\Collection|Deck[] $decks
 * @property \Illuminate\Database\Eloquent\Collection|DecksCard[] $decksCards
 * @property \Illuminate\Database\Eloquent\Collection|UserCard[] $userCards
 */

class CardsVersion extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    use HasFactory;
        public $timestamps = false;

    protected $table = 'cards_versions';

    /**
     * @var array
     */
    protected $fillable = [
        'card_id',
        'image_url',
        'min_price',
        'avg_price',
        'versions'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function card()
    {
        return $this->belongsTo('App\Models\Card');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function decks()
    {
        return $this->hasMany('App\Models\Deck', 'leader_card_version_id');
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
    public function userCards()
    {
        return $this->hasMany('App\Models\UserCard', 'card_version_id');
    }
}
