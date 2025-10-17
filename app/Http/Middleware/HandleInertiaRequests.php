<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'flash' => [
                'success' => fn() => $request->session()->get(key: 'success'),
                'error' => fn() => $request->session()->get(key: 'error'),
                'warning' => fn() => $request->session()->get(key: 'warning'),
                'info' => fn() => $request->session()->get(key: 'info'),
            ],
            'auth' => [
                'user' => $request->user(),
            ],
            'additional' => fn() => $request
                ->session()
                ->get(key: 'additional', default: []),
        ];
    }
}
