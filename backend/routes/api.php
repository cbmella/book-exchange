<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/**
 * Authentication routes
 */
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

/**
 * Routes that require authentication
 */
Route::group(['middleware' => ['auth:api']], function () {
    /**
     * Book routes
     */
    Route::get('books/search', [BookController::class, 'search']);
    Route::resource('books', BookController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

    /**
     * Exchange routes
     */
    Route::post('books/{book}/request-exchange', [ExchangeController::class, 'requestExchange']);
    Route::patch('exchanges/{exchange}/accept', [ExchangeController::class, 'acceptExchange']);

    /**
     * Review routes
     */
    Route::post('exchanges/{exchange}/review', [ReviewController::class, 'store']);
});