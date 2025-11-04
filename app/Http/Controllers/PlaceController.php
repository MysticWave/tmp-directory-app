<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScrapeReviewsRequest;
use App\Http\Requests\Store\StorePlaceRequest;
use App\Http\Requests\Update\UpdatePlaceRequest;
use App\Http\Resources\PlaceResource;
use App\Models\Place;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PlaceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Place/Index', [
            'places' => PlaceResource::collection(
                Place::withCount('reviews')
                    ->orderByDesc('id')
                    ->paginate(25)
                    ->withQueryString(),
            ),
        ]);
    }

    public function store(StorePlaceRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Place::create($validated);

        return redirect()
            ->route('places.index')
            ->with('success', 'Place created successfully.');
    }

    public function update(
        UpdatePlaceRequest $request,
        Place $place,
    ): RedirectResponse {
        $validated = $request->validated();

        $place->update($validated);

        return redirect()
            ->route('places.index')
            ->with('success', 'Place updated successfully.');
    }

    public function scrapeReviews(
        ScrapeReviewsRequest $request,
    ): RedirectResponse {
        $validated = $request->validated();
        $places = Place::whereIn('id', $validated['places_ids'])->get();
        $failed = 0;
        foreach ($places as $place) {
            if (!$place->scrapeReviews()) {
                $failed++;
            }
        }

        if ($failed === 0) {
            return redirect()
                ->route('places.index')
                ->with('success', 'Review scraping initiated successfully.');
        }

        if ($failed === $places->count()) {
            return redirect()
                ->route('places.index')
                ->with(
                    'error',
                    'Failed to initiate review scraping for all places.',
                );
        }

        return redirect()
            ->back()
            ->with(
                'warning',
                'Failed to initiate review scraping for some places.',
            );
    }
}
