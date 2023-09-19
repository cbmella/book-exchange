<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Listar libros del usuario autenticado
    public function index()
    {
        return response()->json(auth()->user()->books);
    }

    // Agregar un libro
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'published_date' => 'required|date',
        ]);

        $book = auth()->user()->books()->create($data);
        return response()->json($book, 201);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $books = Book::search($query, 10);
        return response()->json($books, 200);
    }

    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        if ($book->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($book);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        if ($book->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'published_date' => 'required|date',
        ]);

        $book->update($data);

        return response()->json($book);
    }



    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        if ($book->user_id !== auth()->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $book->delete();

        return response()->json([], 204);
    }


}