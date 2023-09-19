<?php

namespace Tests\Unit;

use App\Models\Exchange;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_belongs_to_user() {
        $user = User::factory()->create();
        $review = Review::factory()->create(['user_id' => $user->id]);
        $this->assertEquals($user->id, $review->user->id);
    }

    public function test_review_belongs_to_exchange() {
        $exchange = Exchange::factory()->create();
        $review = Review::factory()->create(['exchange_id' => $exchange->id]);
        $this->assertEquals($exchange->id, $review->exchange->id);
    }
}
