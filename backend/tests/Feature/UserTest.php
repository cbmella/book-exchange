<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Book;
use App\Models\Exchange;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_books()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->books->contains($book));
    }

    public function test_user_has_exchanges_as_borrower()
    {
        $user = User::factory()->create();
        $exchange = Exchange::factory()->create(['borrower_id' => $user->id]);
        $this->assertTrue($user->exchangesAsBorrower->contains($exchange));
    }

    public function test_user_has_exchanges_as_lender()
    {
        $user = User::factory()->create();
        $exchange = Exchange::factory()->create(['lender_id' => $user->id]);
        $this->assertTrue($user->exchangesAsLender->contains($exchange));
    }

    public function test_user_has_reviews()
    {
        $user = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $user->id]);
        $this->assertTrue($user->reviews->contains($review));
    }
}
