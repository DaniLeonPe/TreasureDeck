<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardsVersion extends Model
{
    use HasFactory;
 public $timestamps = false;
    protected $fillable = [
        'card_id',
        'image_url',
        'min_price',
        'avg_price',
        'versions',
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    

    public function decksCards()
    {
        return $this->hasMany(DecksCard::class, 'card_version_id');
    }

    public function userCards()
    {
        return $this->hasMany(UserCard::class, 'card_version_id');
    }
}
