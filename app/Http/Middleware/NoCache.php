<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class NoCache
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()) {
            $session = Session::getId();
            if ($session != Auth::user()->last_session) {
                return redirect()->route('expiredlogout');
            }
        }
        $response = $next($request);
        if (!$response instanceof BinaryFileResponse) {
            $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }
        return $response;
    }
}
