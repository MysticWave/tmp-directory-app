<?php

use App\Http\Controllers\AiOutputController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PlaceImportController;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('home');

    Route::resource('users', UserController::class)->only([
        'index',
        'store',
        'update',
    ]);

    Route::post('places/scrape-reviews/', [
        PlaceController::class,
        'scrapeReviews',
    ])->name('places.scrape-reviews');
    Route::post('places/process-reviews/', [
        PlaceController::class,
        'processReviews',
    ])->name('places.process-reviews');
    Route::get('places/get-cities', [
        PlaceController::class,
        'getCities',
    ])->name('places.get-cities');
    Route::resource('places', PlaceController::class)->only([
        'index',
        'store',
        'update',
        'show',
    ]);

    Route::get('place-imports/squid-details', [
        PlaceImportController::class,
        'getImportSquidDetails',
    ])->name('place-imports.squid-details');
    Route::resource('place-imports', PlaceImportController::class)->only([
        'index',
        'show',
        'store',
    ]);

    Route::resource('reviews', ReviewController::class)->only([
        'index',
        'show',
    ]);

    Route::resource('prompts', PromptController::class)->only([
        'index',
        'update',
        'store',
    ]);
    Route::get('prompts/find', [PromptController::class, 'find'])->name(
        'prompts.find',
    );

    Route::resource('ai-outputs', AiOutputController::class)->only(['index']);
});

require __DIR__ . '/auth.php';
