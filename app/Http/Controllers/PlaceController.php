<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AiProcessReviews;
use App\Http\Requests\ScrapeReviewsRequest;
use App\Http\Requests\Store\StorePlaceRequest;
use App\Http\Requests\Update\UpdatePlaceRequest;
use App\Http\Resources\PlaceResource;
use App\Models\Place;
use App\Models\Prompt;
use Illuminate\Http\JsonResponse;
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
                    ->querySearch()
                    ->queryOrder()
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

    public function show(Place $place): Response
    {
        $place->load(['reviews', 'aiOutputs']);
        return Inertia::render('Place/Show', [
            'place' => PlaceResource::make($place),
        ]);
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

    public function processReviews(AiProcessReviews $request): RedirectResponse
    {
        $validated = $request->validated();
        $places = Place::whereIn('id', $validated['places_ids'])->get();
        $prompt = Prompt::find($validated['prompt_id']);
        $failed = 0;
        foreach ($places as $place) {
            if (!$place->aiProcessReviews($prompt)) {
                $failed++;
            }
        }

        if ($failed === 0) {
            return redirect()
                ->route('places.index')
                ->with('success', 'Review processing initiated successfully.');
        }

        if ($failed === $places->count()) {
            return redirect()
                ->route('places.index')
                ->with(
                    'error',
                    'Failed to initiate review processing for all places.',
                );
        }

        return redirect()
            ->back()
            ->with(
                'warning',
                'Failed to initiate review processing for some places.',
            );
    }

    public function getCities(): JsonResponse
    {
        $cities = Place::select('city')
            ->distinct()
            ->when(request()->get('term'), function ($query, $term) {
                $query->where('city', 'like', '%' . $term . '%');
            })
            ->orderBy('city')
            ->pluck('city')
            ->map(function ($city) {
                return ['value' => $city, 'label' => $city];
            });

        return response()->json(['data' => $cities]);
    }
}
