<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property Card[] $cards
 */
class Expansion extends Model
{
    /**
     * @var array
     */
        public $timestamps = false;
        use HasFactory;

    protected $fillable = ['id', 'name'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards()
    {
        return $this->hasMany('App\Models\Card');
    }
}
