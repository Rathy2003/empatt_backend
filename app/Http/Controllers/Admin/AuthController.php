<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if(Auth::check()){
            return redirect()->route('dashboard');
        }
        return Inertia::render('Authentication/Login');
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)){
            return redirect()->intended(route('dashboard'));
        }
        return back()->withErrors(["message" => "The provided credentials do not match our records."]);
    }

    public function processLogout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
