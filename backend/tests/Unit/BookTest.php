<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Exchange;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $book;
    protected $exchange;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->book = Book::factory()->create(['user_id' => $this->user->id]);
        $this->exchange = Exchange::factory()->create(['book_id' => $this->book->id]);
    }

    /**
     * Test if a book belongs to its owner.
     *
     * @test
     * @return void
     */
    public function a_book_belongs_to_its_owner()
    {
        $this->assertEquals($this->user->id, $this->book->user->id);
    }

    /**
     * Test if a book has multiple exchanges.
     *
     * @test
     * @return void
     */
    public function a_book_has_multiple_exchanges()
    {
        $exchange2 = Exchange::factory()->create(['book_id' => $this->book->id]);

        $this->assertTrue($this->book->exchanges->contains($this->exchange));
        $this->assertTrue($this->book->exchanges->contains($exchange2));
    }

    /**
     * Test if a book can be accurately searched by title.
     *
     * @test
     * @return void
     */
    public function a_book_can_be_accurately_searched_by_title()
    {
        $title = $this->faker->sentence;
        $book1 = Book::factory()->create(['title' => $title]);
        $book2 = Book::factory()->create(['title' => $this->faker->sentence]);
        $searchedBook = Book::search($title)->first();

        $this->assertEquals($book1->id, $searchedBook->id);
        $this->assertNotEquals($book2->id, $searchedBook->id);
    }

    /**
     * Test if a book can be accurately searched by author.
     *
     * @test
     * @return void
     */
    public function a_book_can_be_accurately_searched_by_author()
    {
        $author = $this->faker->name;
        $book1 = Book::factory()->create(['author' => $author]);
        $book2 = Book::factory()->create(['author' => $this->faker->name]);
        $searchedBook = Book::search($author)->first();

        $this->assertEquals($book1->id, $searchedBook->id);
        $this->assertNotEquals($book2->id, $searchedBook->id);
    }
}