<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $deck_id
 * @property integer $wins
 * @property integer $losses
 * @property Deck $deck
 */
class DecksStat extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    use HasFactory;
        public $timestamps = false;

    protected $table = 'decks_stats';

    /**
     * @var array
     */
    protected $fillable = ['deck_id', 'wins', 'losses', 'dice'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deck()
    {
        return $this->belongsTo('App\Models\Deck');
    }
}
