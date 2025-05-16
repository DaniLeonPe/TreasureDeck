<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $expansion_id
 * @property string $name
 * @property string $collector_number
 * @property string $rarity
 * @property CardsVersion[] $cardsVersions
 * @property Expansion $expansion
 */
class Card extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['expansion_id', 'name', 'collector_number', 'rarity'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cardsVersions()
    {
        return $this->hasMany('App\Models\CardsVersion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function expansion()
    {
        return $this->belongsTo('App\Models\Expansion');
    }
}
