<?php

namespace App\Http\Controllers;

use App\Enums\PlaceImportType;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportPlaceRequest;
use App\Http\Requests\Store\StorePlaceRequest;
use App\Http\Requests\Update\UpdatePlaceRequest;
use App\Http\Resources\PlaceImportResource;
use App\Http\Resources\PlaceResource;
use App\Models\Place;
use App\Models\PlaceImport;
use App\Services\LobstrioService;
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
                    ->paginate(25)
                    ->withQueryString(),
            ),
            'imports' => PlaceImportResource::collection(
                PlaceImport::where('type', PlaceImportType::PLACE)
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

    public function import(ImportPlaceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['squid_id'] = config(
            'services.lobstrio.squids.place_import',
        );
        $import = PlaceImport::create($validated);
        $import->run();

        return redirect()
            ->route('places.index')
            ->with('success', 'Place import initiated successfully.');
    }

    public function scrapeReviews(Place $place): RedirectResponse
    {
        if ($place->scrapeReviews()) {
            return redirect()
                ->route('places.index')
                ->with('success', 'Review scraping initiated successfully.');
        }

        return redirect()
            ->route('places.index')
            ->with('error', 'Failed to initiate review scraping.');
    }
}
