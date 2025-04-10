<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetUserLanguage
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->language) {
            // Use the language from authenticated user
            App::setLocale(Auth::user()->language);
        } elseif (Session::has('locale')) {
            // Use session language if available
            App::setLocale(Session::get('locale'));
        } else {
            // Default to English if no session or user language
            App::setLocale('en');
        }

        return $next($request);
    }
}
