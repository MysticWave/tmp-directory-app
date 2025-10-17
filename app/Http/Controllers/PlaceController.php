<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportPlaceRequest;
use App\Http\Requests\Store\StorePlaceRequest;
use App\Http\Requests\Update\UpdatePlaceRequest;
use App\Http\Resources\PlaceResource;
use App\Models\Place;
use App\Services\LobstrioService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PlaceController extends Controller
{
    public function index(): Response
    {
        $response = app(LobstrioService::class)->listSquids();

        return Inertia::render('Place/Index', [
            'places' => PlaceResource::collection(Place::paginate(25)),
            'squids' => Inertia::defer(
                fn() => collect($response['data'] ?? [])->map(
                    fn(array $item) => [
                        'id' => $item['id'],
                        'name' => $item['name'],
                    ],
                ),
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

        dd(
            app(LobstrioService::class)->getPlaceData(
                squidId: $validated['squid_id'],
                query: $validated['query'],
            ),
        );

        return redirect()
            ->route('places.index')
            ->with('success', 'Place import initiated successfully.');
    }
}
