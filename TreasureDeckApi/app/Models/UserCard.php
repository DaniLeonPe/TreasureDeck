<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $card_version_id
 * @property integer $quantity
 * @property User $user
 * @property CardsVersion $cardsVersion
 */
class UserCard extends Model
{
    /**
     * @var array
     */
        public $timestamps = false;
    use HasFactory;
    protected $fillable = ['user_id', 'card_version_id', 'quantity'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cardsVersion()
    {
        return $this->belongsTo('App\Models\CardsVersion', 'card_version_id');
    }
}
