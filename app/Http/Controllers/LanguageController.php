<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    public function change($lang)
    {
        // Ensure only 'en' and 'id' are allowed
        if (!in_array($lang, ['en', 'id', 'sd'])) {
            abort(400); // Bad request
        }

        // Set the language session for guests
        Session::put('locale', $lang);
        
        // If user is logged in, save preference
        if (Auth::check()) {
            $user = Auth::user();
            $user->language = $lang;
            $user->save();
        }

        return redirect()->back()->with('success', __('messages.success_update_language')); // Reload the page with the new language
    }
}