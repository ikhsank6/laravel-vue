<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleAppearance
{
    /** Default theme per design surface (see DESIGN.md): both surfaces default to light. */
    private const DEFAULTS = ['website' => 'light', 'admin' => 'light'];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $surface = $request->routeIs('home', 'news.*', 'about', 'schemes.*') ? 'website' : 'admin';

        View::share('surface', $surface);
        View::share('appearance', $request->cookie("appearance_{$surface}") ?? self::DEFAULTS[$surface]);

        return $next($request);
    }
}
