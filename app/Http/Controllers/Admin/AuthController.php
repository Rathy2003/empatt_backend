<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail;
use Carbon\Carbon;
use App\Models\OTP;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

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
        return back()->withErrors(["message" => "Invalid Email Address or Password."]);
    }

    public function resetPassword()
    {
        return Inertia::render('Authentication/ResetPassword');
    }

    public function verifyResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'code' => 'required|string|min:6|max:6'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();
        if(!$user){
            return back()->withErrors(['email' => 'No employee found with this email.'])->withInput();
        }

        $otp = OTP::where('code', $request->code)->where('expired_at', '>', now())->where('employee_id', $user->id)->first();
        if(!$otp){
            return back()->withErrors(['message' => 'Invalid code or code expired.'])->withInput();
        }

        $otp->verified_at = now();
        $otp->save();

        return back()->with('success', 'Reset code verified.');
    }

    public function sendResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email'
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        // find employee id by email
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return back()->withErrors(['email' => 'No employee found with this email.'])->withInput();
        }

        // count total today otp sent to this email
        $total_otp_sent = OTP::where('employee_id', $user->id)->whereDate('created_at', now()->toDateString())->count();
        if($total_otp_sent >= 5){
                return back()->withErrors(['email' => 'You have reached the maximum number of OTPs for today.'])->withInput();
        }

        $otp = rand(100000, 999999);
        Mail::to($request->email)->send(new OTPMail($otp));
        $otp_expiry = Carbon::now()->addMinutes(5);
        OTP::create([
            'employee_id' => $user->id,
            'code' => $otp,
            'expired_at' => $otp_expiry,
        ]);
        return back()->with('success', 'Reset code sent to your email.');
    }

    public function processLogout(){
        Auth::logout();
        return redirect()->route('login');
    }

    public function processResetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8|same:password',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();
        if(!$user){
            return back()->withErrors(['email' => 'No employee found with this email.'])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password reset successfully.');
    }
}
