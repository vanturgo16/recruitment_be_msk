<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaptchaController extends Controller
{
    public function generate()
    {
        $captcha = strtoupper(Str::random(5));
        session(['captcha_code' => $captcha]);
        return response()->json(['captcha' => $captcha]);
    }

}
