<?php

namespace Tests\Unit;

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
    
    /**
     * Test if a book belongs to its owner.
     *
     * @return void
     */
    public function test_book_belongs_to_owner()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user->id]);
        $this->assertEquals($user->id, $book->user->id);
    }

    /**
     * Test if a book has exchanges.
     *
     * @return void
     */
    public function test_book_has_exchanges()
    {
        $book = Book::factory()->create();
        $exchange = Exchange::factory()->create(['book_id' => $book->id]);
        $this->assertTrue($book->exchanges->contains($exchange));
    }

    /**
     * Test if a book can be searched by title.
     *
     * @return void
     */
    public function test_book_can_be_searched_by_title()
    {
        $book = Book::factory()->create(['title' => 'Test Book']);
        $searchedBook = Book::search('Test Book')->first();
        $this->assertEquals($book->id, $searchedBook->id);
    }

    /**
     * Test if a book can be searched by author.
     *
     * @return void
     */
    public function test_book_can_be_searched_by_author()
    {
        $book = Book::factory()->create(['author' => 'Test Author']);
        $searchedBook = Book::search('Test Author')->first();
        $this->assertEquals($book->id, $searchedBook->id);
    }
}