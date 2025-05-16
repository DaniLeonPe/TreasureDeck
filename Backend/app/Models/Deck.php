<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $leader_card_version_id
 * @property string $name
 * @property DecksStat[] $decksStats
 * @property CardsVersion $cardsVersion
 * @property User $user
 */
class Deck extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'leader_card_version_id', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function decksStats()
    {
        return $this->hasMany('App\Models\DecksStat');
    }

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
}
