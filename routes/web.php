<?php

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
});

require __DIR__ . '/auth.php';
