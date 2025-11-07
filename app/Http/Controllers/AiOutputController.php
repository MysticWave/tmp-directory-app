<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\AiOutputResource;
use App\Models\AiOutput;
use Inertia\Inertia;
use Inertia\Response;

class AiOutputController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('AiOutput/Index', [
            'ai_outputs' => AiOutputResource::collection(
                AiOutput::queryOrder()
                    ->paginate(25)
                    ->withQueryString(),
            ),
        ]);
    }
}
