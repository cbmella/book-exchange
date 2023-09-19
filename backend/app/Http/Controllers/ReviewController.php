<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Review;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Exchange $exchange
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Exchange $exchange)
    {
        $data = $this->validateReview($request);

        if ($request->user()->id !== $exchange->borrower_id) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $review = new Review($data);
        $review->user_id = $request->user()->id;
        $exchange->review()->save($review);

        return response()->json($review, Response::HTTP_CREATED);
    }

    /**
     * Validate the request data for a review.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    private function validateReview(Request $request): array
    {
        return $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:255',
        ]);
    }
}
