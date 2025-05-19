<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $leader_card_version_id
 * @property string $name
 * 
 * @property CardsVersion $cardsVersion
 * @property User $user
 * @property \Illuminate\Database\Eloquent\Collection|DecksCard[] $decksCards
 * @property \Illuminate\Database\Eloquent\Collection|DecksStat[] $decksStats
 */

class Deck extends Model
{
    /**
     * @var array
     */
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['user_id', 'leader_card_version_id', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cardsVersion()
    {
        return $this->belongsTo('App\Models\CardsVersion', 'leader_card_version_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function decksCards()
    {
        return $this->hasMany('App\Models\DecksCard');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function decksStats()
    {
        return $this->hasMany('App\Models\DecksStat');
    }
}
