<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Illuminate\Support\Carbon $email_verified_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Book[] $books
 * @property-read \Illuminate\Database\Eloquent\Collection|Exchange[] $exchangesAsBorrower
 * @property-read \Illuminate\Database\Eloquent\Collection|Exchange[] $exchangesAsLender
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the JWT identifier for the user.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the books associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Get the exchanges where the user is the borrower.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exchangesAsBorrower()
    {
        return $this->hasMany(Exchange::class, 'borrower_id');
    }

    /**
     * Get the exchanges where the user is the lender.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exchangesAsLender()
    {
        return $this->hasMany(Exchange::class, 'lender_id');
    }
}