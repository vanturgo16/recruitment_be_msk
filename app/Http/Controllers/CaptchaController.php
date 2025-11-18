<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaptchaController extends Controller
{
    public function generate()
    {
        // $captcha = strtoupper(Str::random(5));
        
        // Define allowed characters (A-Z and 1-9, excluding O and 0)
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ123456789';
        // Randomly pick 5 characters
        $captcha = '';
        for ($i = 0; $i < 5; $i++) {
            $captcha .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        session(['captcha_code' => $captcha]);
        return response()->json(['captcha' => $captcha]);
    }
}
