<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

// Model
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $credentials = [
            'email' => $email,
            'password' => $password
        ];
        $dologin = Auth::attempt($credentials);
        if ($dologin) {
            $user = User::where('email', $request->email)->first();
            if ($user->is_active == 1) {
                $session = Session::getId();
                User::where('email', $email)->update([
                    'last_login' => now(),
                    'login_counter' => $user->login_counter + 1,
                    'last_session' => $session,
                ]);
                return redirect()->route('dashboard')->with('success', __('messages.success_login'));
            } else {
                return redirect()->route('login')->with('fail', 'Your Account Is Innactive, Contact Your Administrator');
            }
        } else {
            return redirect()->route('login')->with('fail', 'Wrong Email or Password');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Success Logout');
    }

    public function expiredlogout()
    {
        Auth::logout();
        return redirect()->route('login')->with('info', 'Your session has expired or has been logged in to another device.');
    }
}
