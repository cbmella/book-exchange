<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Review
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property int $exchange_id
 * @property int $rating
 * @property string $comment
 * @property User $user
 * @property Exchange $exchange
 */

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['rating', 'comment'];

    /**
     * Get the user that owns the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the exchange that the review belongs to.
     */
    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }
}