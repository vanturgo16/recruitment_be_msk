<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HandleNotFound
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (NotFoundHttpException $e) {
            return response()->view('errors.404', [], 404);
        }
    }
}
