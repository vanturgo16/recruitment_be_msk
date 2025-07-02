<?php

namespace App\Http\Controllers;

use App\Models\MstRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

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
        //dd('hai');
        $request->validate([
            'captcha_input' => 'required|in:' . session('captcha_code'),
        ], [
            'captcha_input.required' => 'Kode CAPTCHA harus diisi.',
            'captcha_input.in' => 'Kode CAPTCHA yang dimasukkan tidak sesuai.',
        ]);
        
        $email = $request->email;
        $password = $request->password;

        // Validasi apakah password sudah lewat 180 hari sejak last_modified_password_at
        $user = User::where('email', $email)->first();
        if ($user && $user->last_modified_password_at) {
            $passwordAge = now()->diffInDays($user->last_modified_password_at);
            $regeneratePeriod = MstRules::where('rule_name', 'Regenerate Password Period')->value('rule_value');
            if ($passwordAge > (int)$regeneratePeriod) {
                // Simpan email ke session, redirect tanpa email di URL
                session(['change_password_email' => $email]);
                return redirect()->route('password.change')->with('fail', "Your password is older than " . $regeneratePeriod . " days, please regenerate your password.");
            }
        }

        $credentials = [
            'email' => $email,
            'password' => $password
        ];
        $dologin = Auth::attempt($credentials);
        if ($dologin) {
            $user = User::where('email', $request->email)->first();
            if ($user->role === 'Candidate') {
                Auth::logout();
                return redirect()->route('login')->with('fail', 'Login is not allowed for your role.');
            }
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

    public function changePassword(Request $request)
    {
        // Ambil email dari session jika ada, jika tidak dari request
        $email = session('change_password_email', $request->email);
        return view('auth.change_password', ['email' => $email]);
    }
    
    public function updatePassword(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'old_password' => 'required',
            'new_password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[0-9]).{8,}$/', // minimal 8 karakter dan ada angka
            ],
            'email' => 'required|email',
        ], [
            'old_password.required' => 'Old password is required.',
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.regex' => 'New password must contain at least 1 number.',
            'email.required' => 'Email is required.',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->back()->with('fail', 'User not found.');
        }

        // Cek apakah password lama benar
        if (!\Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->with('fail', 'Old password does not match.');
        }

        // Update password dan last_modified_password_at
        $user->password = bcrypt($request->new_password);
        $user->last_modified_password_at = now();
        $user->save();

        // Setelah berhasil ubah password, hapus email dari session
        session()->forget('change_password_email');

        return redirect()->route('login')->with('success', 'Password has been changed successfully. Please login again.');
    }
}
