<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ReviewController extends Controller
{
    public function index(): Response
    {
        $reviews = Review::with('place')
            ->orderBy('place_id')
            ->when(
                request()->input('place_id'),
                fn($query, $placeId) => $query->where('place_id', $placeId),
            )
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Review/Index', [
            'reviews' => ReviewResource::collection($reviews),
        ]);
    }

    public function show(Review $review): RedirectResponse
    {
        // not implemented yet
    }
}
