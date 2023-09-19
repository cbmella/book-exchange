<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Exchange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExchangeManagementTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A user must be able to request a trade
     */
    public function test_user_can_request_exchange()
    {
        $borrower = User::factory()->create();
        $lender = User::factory()->create();
        $book = Book::factory()->for($lender)->create();

        $this->actingAs($borrower, 'api')
            ->post("api/books/{$book->id}/request-exchange")
            ->assertStatus(201);
    }

    /**
     * The owner of the book must be able to accept or reject a trade
     */
    public function test_lender_can_accept_exchange_request()
    {
        $borrower = User::factory()->create();
        $lender = User::factory()->create();
        $book = Book::factory()->for($lender)->create();
        $exchange = Exchange::factory()->for($book)->create(['borrower_id' => $borrower->id, 'lender_id' => $lender->id]);

        $this->actingAs($lender, 'api')
            ->patch("api/exchanges/{$exchange->id}/accept")
            ->assertStatus(200);
    }
}
