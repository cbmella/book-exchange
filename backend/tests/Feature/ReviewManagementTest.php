<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Exchange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewManagementTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A user must be able to rate another user after an exchange
     */
    public function test_user_can_leave_review()
    {
        $borrower = User::factory()->create();
        $lender = User::factory()->create();
        $book = Book::factory()->for($lender)->create();
        $exchange = Exchange::factory()->for($book)->create(['borrower_id' => $borrower->id, 'lender_id' => $lender->id]);

        $reviewData = [
            'user_id' => $borrower->id,
            'exchange_id' => $exchange->id, // Añadido exchange_id
            'rating' => 5,
            'comment' => 'Great exchange experience!'
        ];
        

        $this->actingAs($borrower, 'api')
            ->post("api/exchanges/{$exchange->id}/review", $reviewData)
            ->assertStatus(201);
    }
}
