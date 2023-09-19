<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class BookControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     *  Test that an unauthenticated user cannot retrieve books.
     *
     * @test
     * @return void
     */
    public function an_authenticated_user_can_retrieve_their_books()
    {
        $user = User::factory()->create();
        $books = Book::factory()->count(3)->create(['user_id' => $user->id]);

        $this->actingAs($user, 'api')
            ->get('/api/books')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3)
            ->assertJson($books->toArray());
    }

    /**
     *  Test that an authenticated user can create a new book.
     *
     * @test
     * @return void
     */
    public function an_authenticated_user_can_create_a_new_book()
    {
        $user = User::factory()->create();
        $bookData = [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name,
            'published_date' => $this->faker->date,
        ];

        $this->actingAs($user, 'api')
            ->post('/api/books', $bookData)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson($bookData);

        $this->assertDatabaseHas('books', $bookData + ['user_id' => $user->id]);
    }

    /**
     *  Test that books can be searched by title or author.
     *
     * @test
     * @return void
     */
    public function books_can_be_searched_by_title_or_author()
    {
        $user = User::factory()->create();
        $title = $this->faker->sentence(3);
        $author = $this->faker->name;

        Book::factory()->create([
            'title' => $title,
            'author' => $author,
            'user_id' => $user->id
        ]);

        Book::factory()->count(2)->create(['user_id' => $user->id]);

        $this->actingAs($user, 'api')
            ->get('/api/books/search?query=' . $title)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['title' => $title]);

        $this->actingAs($user, 'api')
            ->get('/api/books/search?query=' . $author)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['author' => $author]);
    }

    /**
     *  Test that a search for a nonexistent book term returns an empty result.
     *
     * @test
     * @return void
     */
    public function searching_for_a_nonexistent_book_term_returns_empty_result()
    {
        $user = User::factory()->create();
        Book::factory()->count(3)->create(['user_id' => $user->id]);

        $this->actingAs($user, 'api')
            ->get('/api/books/search?query=ThisBookDoesNotExist12345')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(0, 'data');
    }


    /**
     *  Test that an authenticated user can retrieve details of a specific book.
     *
     * @test
     * @return void
     */
    public function an_authenticated_user_can_retrieve_details_of_a_specific_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user, 'api')
            ->get('/api/books/' . $book->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($book->toArray());
    }


    /**
     *  Test that attempting to access a book that does not belong to the authenticated user results in an error.
     *
     * @test
     * @return void
     */
    public function accessing_a_book_that_does_not_belong_to_the_authenticated_user_returns_an_error()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user1->id]);

        $this->actingAs($user2, 'api')
            ->get('/api/books/' . $book->id)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }


    /**
     *  Test that an authenticated user can update a book they own.
     *
     * @test
     * @return void
     */
    public function an_authenticated_user_can_update_their_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user->id]);
        $bookData = [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name,
            'published_date' => $this->faker->date,
        ];

        $this->actingAs($user, 'api')
            ->put('/api/books/' . $book->id, $bookData)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($bookData);

        $this->assertDatabaseHas('books', $bookData + ['id' => $book->id, 'user_id' => $user->id]);
    }


    /**
     *  Test that attempting to update a book that does not belong to the authenticated user results in an error.
     *
     * @test
     * @return void
     */
    public function updating_a_book_that_does_not_belong_to_the_authenticated_user_returns_an_error()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user1->id]);
        $bookData = [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name,
            'published_date' => $this->faker->date,
        ];

        $this->actingAs($user2, 'api')
            ->put('/api/books/' . $book->id, $bookData)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }


    /**
     *  Test that an authenticated user can delete a book they own.
     *
     * @test
     * @return void
     */
    public function an_authenticated_user_can_delete_their_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user, 'api')
            ->delete('/api/books/' . $book->id)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    /**
     *  Test that attempting to delete a book that does not belong to the authenticated user results in an error.
     *
     * @test
     * @return void
     */
    public function deleting_a_book_that_does_not_belong_to_the_authenticated_user_returns_an_error()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $book = Book::factory()->create(['user_id' => $user1->id]);

        $this->actingAs($user2, 'api')
            ->delete('/api/books/' . $book->id)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}