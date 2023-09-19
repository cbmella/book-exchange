<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_a_list_of_books_for_authenticated_user()
    {
        $user = User::factory()->create();
        $books = Book::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'api')->get('/api/books');

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJson($books->toArray());
    }

    /** @test */
    public function it_creates_a_new_book_for_authenticated_user()
    {
        $user = User::factory()->create();
        $bookData = [
            'title' => 'The Catcher in the Rye',
            'author' => 'J.D. Salinger',
            'published_date' => '1951-07-16',
        ];

        $response = $this->actingAs($user, 'api')->post('/api/books', $bookData);

        $response->assertStatus(201)
            ->assertJson($bookData);

        $this->assertDatabaseHas('books', $bookData + ['user_id' => $user->id]);
    }

    /** @test */
    public function it_searches_for_books_by_title_or_author()
    {
        $user = User::factory()->create();

        // Create books with specific titles and authors
        Book::factory()->create([
            'title' => 'Specific Title',
            'author' => 'Specific Author',
            'user_id' => $user->id
        ]);
        Book::factory()->count(2)->create(['user_id' => $user->id]); // Other 2 books

        // Search by specific title
        $response = $this->actingAs($user, 'api')->get('/api/books/search?query=Specific Title');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Specific Title']);
        
        // Search by specific author
        $response = $this->actingAs($user, 'api')->get('/api/books/search?query=Specific Author');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['author' => 'Specific Author']);
    }

    /** @test */
    public function it_returns_empty_result_when_searching_for_nonexistent_book()
    {
        $user = User::factory()->create();
        Book::factory()->count(3)->create(['user_id' => $user->id]);

        // Search for a truly nonexistent term
        $response = $this->actingAs($user, 'api')->get('/api/books/search?query=ThisBookDoesNotExist12345');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    /** @test */
    public function it_returns_a_single_book_for_authenticated_user()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'api')->get('/api/books/' . $book->id);

        $response->assertStatus(200)
            ->assertJson($book->toArray());
    }

    /** @test */
    public function it_returns_an_error_when_trying_to_access_a_book_that_does_not_belong_to_authenticated_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2, 'api')->get('/api/books/' . $book->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_updates_a_book_for_authenticated_user()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user->id]);
        $bookData = [
            'title' => 'New Title',
            'author' => 'New Author',
            'published_date' => '2021-01-01',
        ];

        $response = $this->actingAs($user, 'api')->put('/api/books/' . $book->id, $bookData);

        $response->assertStatus(200)
            ->assertJson($bookData);

        $this->assertDatabaseHas('books', $bookData + ['id' => $book->id, 'user_id' => $user->id]);
    }

    /** @test */
    public function it_returns_an_error_when_trying_to_update_a_book_that_does_not_belong_to_authenticated_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user1->id]);
        $bookData = [
            'title' => 'New Title',
            'author' => 'New Author',
            'published_date' => '2021-01-01',
        ];

        $response = $this->actingAs($user2, 'api')->put('/api/books/' . $book->id, $bookData);

        $response->assertStatus(403);
    }

    /** @test */
    public function it_deletes_a_book_for_authenticated_user()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'api')->delete('/api/books/' . $book->id);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    /** @test */
    public function it_returns_an_error_when_trying_to_delete_a_book_that_does_not_belong_to_authenticated_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2, 'api')->delete('/api/books/' . $book->id);

        $response->assertStatus(403);
    }
}