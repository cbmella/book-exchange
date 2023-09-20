<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Exchange;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExchangeController extends Controller
{
    /**
     * Request an exchange for a book.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestExchange(Request $request, Book $book)
    {
        $exchange = $this->createExchangeRequest($book);

        return response()->json(['message' => 'Exchange requested successfully'], Response::HTTP_CREATED);
    }

    /**
     * Accept an exchange request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Exchange $exchange
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptExchange(Request $request, Exchange $exchange)
    {
        $exchange->status = 'accepted';
        $exchange->save();

        return response()->json(['message' => 'Exchange accepted successfully'], Response::HTTP_OK);
    }

    /**
     * Create a new exchange request.
     *
     * @param  \App\Models\Book $book
     * @return \App\Models\Exchange
     */
    private function createExchangeRequest(Book $book): Exchange
    {
        $exchange = new Exchange();
        $exchange->borrower_id = auth()->id();
        $exchange->lender_id = $book->user_id;
        $exchange->book_id = $book->id;
        $exchange->status = 'requested';
        $exchange->save();

        return $exchange;
    }
}