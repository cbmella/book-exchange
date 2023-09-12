<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Exchange;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_book_belongs_to_owner()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user->id]);
        $this->assertEquals($user->id, $book->owner->id);
    }

    public function test_book_has_exchanges()
    {
        $book = Book::factory()->create();
        $exchange = Exchange::factory()->create(['book_id' => $book->id]);
        $this->assertTrue($book->exchanges->contains($exchange));
    }

    public function test_book_has_reviews()
    {
        $book = Book::factory()->create();
        $review = Review::factory()->create(['book_id' => $book->id]);
        $this->assertTrue($book->reviews->contains($review));
    }
}
