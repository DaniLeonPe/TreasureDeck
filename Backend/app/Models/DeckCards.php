<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $card_version_id
 * @property CardsVersion $cardsVersion
 * @property User $user
 */
class DeckCards extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'decks_cards';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'card_version_id'];

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
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
