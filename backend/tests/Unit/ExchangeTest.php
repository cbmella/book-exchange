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
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $book;
    protected $exchange;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->book = Book::factory()->create();
        $this->exchange = Exchange::factory()->create([
            'borrower_id' => $this->user->id,
            'lender_id' => $this->user->id,
            'book_id' => $this->book->id
        ]);
    }

    /**
     * Test if an exchange belongs to a borrower.
     *
     * @test
     * @return void
     */
    public function an_exchange_belongs_to_a_borrower()
    {
        $this->assertEquals($this->user->id, $this->exchange->borrower->id);
    }

    /**
     * Test if an exchange belongs to a lender.
     *
     * @test
     * @return void
     */
    public function an_exchange_belongs_to_a_lender()
    {
        $this->assertEquals($this->user->id, $this->exchange->lender->id);
    }

    /**
     * Test if an exchange belongs to a book.
     *
     * @test
     * @return void
     */
    public function an_exchange_belongs_to_a_book()
    {
        $this->assertEquals($this->book->id, $this->exchange->book->id);
    }
}