<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $base = parent::share($request);

        // Ambil quote
        $quoteString = Inspiring::quote();
        $message = $quoteString;
        $author = null;
        if (strpos($quoteString, '-') !== false) {
            [$m, $a] = explode('-', $quoteString, 2);
            $message = trim($m);
            $author = trim($a);
        }

        $extra = [
            'flash' => [
                'success' => function () use ($request) {
                    return $request->session()->get('success');
                },
                'error' => function () use ($request) {
                    return $request->session()->get('error');
                },
            ],
            'name' => config('app.name'),
            'quote' => [
                'message' => $message,
                'author' => $author,
            ],
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => array_merge((new Ziggy())->toArray(), [
                'location' => $request->url(),
            ]),
            'sidebarOpen' => !$request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];

        return array_merge($base, $extra);
    }
}
