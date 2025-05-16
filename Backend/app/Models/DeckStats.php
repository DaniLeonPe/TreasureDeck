<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $deck_id
 * @property integer $wins
 * @property integer $losses
 * @property Deck $deck
 */
class DeckStats extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'decks_stats';

    /**
     * @var array
     */
    protected $fillable = ['deck_id', 'wins', 'losses'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deck()
    {
        return $this->belongsTo('App\Models\Deck');
    }
}
