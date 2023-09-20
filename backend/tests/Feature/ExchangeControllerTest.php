<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Exchange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExchangeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  Test that a user can leave a review after an exchange.
     *
     * @test
     * @return void
     */
    public function a_user_can_request_an_exchange()
    {
        $borrower = User::factory()->create();
        $lender = User::factory()->create();
        $book = Book::factory()->for($lender)->create();

        $this->actingAs($borrower, 'api')
            ->post("api/books/{$book->id}/request-exchange")
            ->assertStatus(201);
    }

    /**
     *  Test that a user cannot request an exchange for a book they own.
     *
     * @test
     * @return void
     */
    public function a_lender_can_accept_an_exchange_request()
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