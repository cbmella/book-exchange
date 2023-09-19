<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Exchange;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    // Método para solicitar un intercambio de libro
    public function requestExchange(Request $request, Book $book)
    {
        // Aquí implementas la lógica para crear una solicitud de intercambio
        // Por ejemplo, crear un nuevo registro en la tabla 'exchanges'

        $exchange = new Exchange();
        $exchange->borrower_id = auth()->id();
        $exchange->lender_id = $book->user_id;
        $exchange->book_id = $book->id;
        $exchange->status = 'requested';  // Esto supone que tienes un campo 'status' en tu modelo Exchange
        $exchange->save();

        return response()->json(['message' => 'Exchange requested successfully'], 201);
    }

    // Método para aceptar un intercambio
    public function acceptExchange(Request $request, Exchange $exchange)
    {
        // Aquí implementas la lógica para aceptar la solicitud de intercambio
        // Por ejemplo, cambiar el estado de 'requested' a 'accepted'

        $exchange->status = 'accepted';
        $exchange->save();

        return response()->json(['message' => 'Exchange accepted successfully'], 200);
    }
}
