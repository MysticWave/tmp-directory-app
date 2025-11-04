<?php

namespace App\Http\Controllers;

use App\Enums\PlaceImportTaskType;
use App\Enums\PlaceImportType;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportPlaceRequest;
use App\Http\Requests\ScrapeReviewsRequest;
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

class PlaceImportController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('PlaceImport/Index', [
            'imports' => PlaceImportResource::collection(
                PlaceImport::paginate(25)->withQueryString(),
            ),
        ]);
    }

    public function store(ImportPlaceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['squid_id'] = config(
            'services.lobstrio.squids.place_import',
        );

        if ($validated['task_type'] == PlaceImportTaskType::PARAMS->value) {
            $validated['params'] = [
                'category' => $validated['category'],
                'country' => $validated['country'],
                'city' => $validated['city'],
            ];
            unset($validated['query']);
        }

        unset(
            $validated['category'],
            $validated['country'],
            $validated['city'],
        );

        $import = PlaceImport::create($validated);
        $import->run();

        return redirect()
            ->back()
            ->with('success', 'Place import initiated successfully.');
    }
}
