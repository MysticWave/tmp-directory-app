<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StorePromptRequest;
use App\Http\Requests\Update\UpdatePromptRequest;
use App\Http\Resources\PromptResource;
use App\Models\Prompt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PromptController extends Controller
{
    public function index(): Response
    {
        $prompts = Prompt::queryOrder()
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Prompt/Index', [
            'prompts' => PromptResource::collection($prompts),
        ]);
    }

    public function store(StorePromptRequest $request): RedirectResponse
    {
        $attributes = $request->validated();
        $attributes['user_id'] = auth()->id();

        Prompt::create($attributes);

        return redirect()
            ->route('prompts.index')
            ->with('success', 'Prompt created successfully.');
    }

    public function update(
        UpdatePromptRequest $request,
        Prompt $prompt,
    ): RedirectResponse {
        $attributes = $request->validated();

        $prompt->update($attributes);

        return redirect()
            ->route('prompts.index')
            ->with('success', 'Prompt updated successfully.');
    }

    public function find(): JsonResponse
    {
        $prompts = Prompt::query()
            ->when(request()->get('term'), function ($query, $term) {
                $query->where('name', 'like', '%' . $term . '%');
            })
            ->paginate(20)
            ->getCollection()
            ->map(function ($prompt) {
                return ['value' => $prompt->id, 'label' => $prompt->name];
            });

        return response()->json(['data' => $prompts]);
    }
}
