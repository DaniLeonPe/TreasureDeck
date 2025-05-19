<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $expansion_id
 * @property string $name
 * @property string $collector_number
 * @property string $rarity
 * @property Expansion $expansion
 * @property CardsVersion[] $cardsVersions
 */
class Card extends Model
{
    /**
     * @var array
     */
            use HasFactory;

        public $timestamps = false;

    protected $fillable = ['expansion_id', 'name', 'collector_number', 'rarity'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function expansion()
    {
        return $this->belongsTo('App\Models\Expansion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cardsVersions()
    {
        return $this->hasMany('App\Models\CardsVersion');
    }
}
