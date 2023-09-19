<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Book
 * @package App\Models
 *
 * @property int $id
 * @property string $title
 * @property string $author
 * @property string $published_date
 * @property int $user_id
 * @property User $user
 * @property Exchange[] $exchanges
 * @property Review[] $reviews
 */
class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'author', 'published_date'];

    /**
     * Get the user that owns the book.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the exchanges for the book.
     */
    public function exchanges()
    {
        return $this->hasMany(Exchange::class);
    }

    /**
     * Get the reviews for the book.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Search for books by title or author.
     *
     * @param string $query
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function search($query, $perPage = 10)
    {
        return empty($query) ? static::query()->paginate($perPage)
            : static::where('title', 'like', '%' . $query . '%')
                ->orWhere('author', 'like', '%' . $query . '%')
                ->paginate($perPage);
    }
}