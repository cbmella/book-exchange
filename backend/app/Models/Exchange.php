<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Exchange
 * @package App\Models
 *
 * @property int $id
 * @property int $borrower_id
 * @property int $lender_id
 * @property int $book_id
 * @property string $status
 * @property string $due_date
 * @property string $returned_at
 * @property User $borrower
 * @property User $lender
 * @property Book $book
 * @property Review $review
 */


class Exchange extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'borrower_id',
        'lender_id',
        'book_id',
        'status',
        'due_date',
        'returned_at',
    ];

    /**
     * Get the borrower that owns the exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    /**
     * Get the lender that owns the exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lender()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    /**
     * Get the book that owns the exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the review associated with the exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }
}