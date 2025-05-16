<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password_hash
 * @property string $role
 * @property boolean $email_verified
 * @property string $email_verification_token
 * @property UserCard[] $userCards
 * @property DecksCard[] $decksCards
 * @property Deck[] $decks
 */
class User extends Authenticatable implements JWTSubject
{

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        //lo que pongamos en este array se agrega al tokey
        return [
            'rol' => $this->role,
            'name' => $this->name
        ];
    }
    /**
     * @var array
     */
    protected $fillable = ['name', 'email', 'password_hash', 'role', 'email_verified', 'email_verification_token'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userCards()
    {
        return $this->hasMany('App\Models\UserCard');
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
    public function decks()
    {
        return $this->hasMany('App\Models\Deck');
    }
}
