<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    /**
     * Display a listing of the authenticated user's books.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(auth()->user()->books);
    }

    /**
     * Store a newly created book in storage for the authenticated user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $this->validateBook($request);
        $book = auth()->user()->books()->create($data);
        return response()->json($book, Response::HTTP_CREATED);
    }

    /**
     * Search for books based on a query.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $books = Book::search($query, 10);
        return response()->json($books, Response::HTTP_OK);
    }

    /**
     * Display the specified book.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $book = Book::find($id);
        return $this->respondWithBook($book);
    }

    /**
     * Update the specified book in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        if (!$book || $book->user_id !== auth()->user()->id) {
            return $this->respondWithBook($book);
        }
        $data = $this->validateBook($request);
        $book->update($data);
        return response()->json($book);
    }

    /**
     * Remove the specified book from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        if (!$book || $book->user_id !== auth()->user()->id) {
            return $this->respondWithBook($book);
        }
        $book->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * Validate the book request data.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    private function validateBook(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'published_date' => 'required|date',
        ]);
    }

    /**
     * Respond based on the book's existence and ownership.
     *
     * @param  \App\Models\Book|null $book
     * @return \Illuminate\Http\JsonResponse
     */
    private function respondWithBook(?Book $book)
    {
        if (!$book) {
            return response()->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }
        if ($book->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }
        return response()->json($book);
    }
}