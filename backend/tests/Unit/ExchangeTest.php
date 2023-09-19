<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Exchange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExchangeTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_exchange_has_borrower() {
        $user = User::factory()->create();
        $exchange = Exchange::factory()->create(['borrower_id' => $user->id]);
        $this->assertEquals($user->id, $exchange->borrower->id);
    }

    public function test_exchange_has_lender() {
        $user = User::factory()->create();
        $exchange = Exchange::factory()->create(['lender_id' => $user->id]);
        $this->assertEquals($user->id, $exchange->lender->id);
    }

    public function test_exchange_has_book() {
        $book = Book::factory()->create();
        $exchange = Exchange::factory()->create(['book_id' => $book->id]);
        $this->assertEquals($book->id, $exchange->book->id);
    }
}
