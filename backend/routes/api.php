<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/**
 * Authentication routes
 */
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');

/**
 * Routes that require authentication
 */
Route::group(['middleware' => ['auth:api']], function () {
    /**
     * Book routes
     */
    Route::get('books/search', [BookController::class, 'search'])->name('books.search');
    Route::resource('books', BookController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

    /**
     * Exchange routes
     */
    Route::post('books/{book}/request-exchange', [ExchangeController::class, 'requestExchange'])->name('books.request-exchange');
    Route::patch('exchanges/{exchange}/accept', [ExchangeController::class, 'acceptExchange'])->name('exchanges.accept');

    /**
     * Review routes
     */
    Route::post('exchanges/{exchange}/review', [ReviewController::class, 'store'])->name('reviews.store');
});