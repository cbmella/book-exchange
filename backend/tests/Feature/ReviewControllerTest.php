<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Exchange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     *  Test that a user can leave a review after an exchange.
     *
     * @test
     * @return void
     */
    public function a_user_can_leave_a_review_after_an_exchange()
    {
        $borrower = User::factory()->create();
        $lender = User::factory()->create();
        $book = Book::factory()->for($lender)->create();
        $exchange = Exchange::factory()->for($book)->create(['borrower_id' => $borrower->id, 'lender_id' => $lender->id]);

        $reviewData = [
            'user_id' => $borrower->id,
            'exchange_id' => $exchange->id,
            'rating' => 5,
            'comment' => 'Great exchange experience!'
        ];

        $this->actingAs($borrower, 'api')
            ->post("api/exchanges/{$exchange->id}/review", $reviewData)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('reviews', $reviewData);
    }

    /**
     *  Test that a user cannot leave a review for an exchange they were not a part of.
     *
     * @test
     * @return void
     */
    public function a_review_can_be_stored_with_valid_data()
    {
        $user = User::factory()->create();
        $exchange = Exchange::factory()->create(['borrower_id' => $user->id]);
        $data = [
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->sentence,
        ];

        $this->actingAs($user, 'api')
            ->postJson(route('reviews.store', $exchange), $data)
            ->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('reviews', $data);
    }

    /**
     *  Test that a user cannot leave a review for an exchange they were not a part of.
     *
     * @test
     * @return void
     */
    public function a_review_cannot_be_stored_with_invalid_data()
    {
        $user = User::factory()->create();
        $exchange = Exchange::factory()->create(['borrower_id' => $user->id]);
        $data = [
            'rating' => $this->faker->numberBetween(6, 10),
            'comment' => $this->faker->sentence,
        ];

        $this->actingAs($user, 'api')
            ->postJson(route('reviews.store', $exchange), $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['rating']);

        $this->assertDatabaseMissing('reviews', $data);
    }

    /**
     * Test that a user cannot leave a review for an exchange they were not a part of.
     *
     * @test
     * @return void
     */
    public function an_unauthorized_user_cannot_store_a_review()
    {
        $user = User::factory()->create();
        $exchange = Exchange::factory()->create();
        $data = [
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->sentence,
        ];

        $this->actingAs($user, 'api')
            ->postJson(route('reviews.store', $exchange), $data)
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertDatabaseMissing('reviews', $data);
    }

    /**
     *  Test that a user cannot leave a review for an exchange they were not a part of.
     *
     * @test
     * @return void
     */
    public function a_review_cannot_be_stored_without_a_rating()
    {
        $user = User::factory()->create();
        $exchange = Exchange::factory()->create(['borrower_id' => $user->id]);
        $data = [
            'comment' => $this->faker->sentence,
        ];

        $this->actingAs($user, 'api')
            ->postJson(route('reviews.store', $exchange), $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['rating']);

        $this->assertDatabaseMissing('reviews', $data);
    }

    /**
     *  Test that a user cannot leave a review for an exchange they were not a part of.
     *
     * @test
     * @return void
     */
    public function a_review_cannot_be_stored_without_a_comment()
    {
        $user = User::factory()->create();
        $exchange = Exchange::factory()->create(['borrower_id' => $user->id]);
        $data = [
            'rating' => $this->faker->numberBetween(1, 5),
        ];

        $this->actingAs($user, 'api')
            ->postJson(route('reviews.store', $exchange), $data)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['comment']);

        $this->assertDatabaseMissing('reviews', $data);
    }

    /**
     *  Test that a user cannot leave a review for an exchange they were not a part of.
     *
     * @test
     * @return void
     */
    public function a_review_cannot_be_stored_for_an_invalid_exchange()
    {
        $user = User::factory()->create();
        $exchange = Exchange::factory()->create(['borrower_id' => $user->id]);
        $data = [
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->sentence,
        ];

        $this->actingAs($user, 'api')
            ->postJson(route('reviews.store', $exchange->id + 1), $data)
            ->assertStatus(Response::HTTP_NOT_FOUND);

        $this->assertDatabaseMissing('reviews', $data);
    }
}