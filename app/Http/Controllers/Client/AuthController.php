<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{
    private $key = 'eyJhbGciOiJIUzI1NiJ9.eyJSb2xlIjoiQWRtaW4iLCJJc3N1ZXIiOiJJc3N1ZXIiLCJVc2VybmFtZSI6IkphdmFJblVzZSIsImV4cCI6MTc0OTcxNDE5NCwiaWF0IjoxNzQ5NzE0MTk0fQ.lhbnaIUiJ_p_xD6YkcdK9tyqkY6tTOeC7k-b13NA0qc';
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->role(['admin','employee'])->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['email' => 'Invalid email or password'], 400);
        }

        $payload = [
            'id' => $user->id,
            'first_name' => $user->firstname,
            'last_name' => $user->lastname,
            'email' => $user->email,
            'bio' => $user->bio,
            'dob' => $user->dob,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
            'photo' => $user->photo,
            'position_id' => $user->position_id,
            'role' => $user->roles->first()->name,
            'hire_date' => $user->hire_date,
            'company_id' => $user->company_id,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 30)
        ];

        $token = JWT::encode($payload, $this->key, 'HS256');

        return response()->json(['message' => 'Login successful', 'token' => $token], 200);
    }


    public function verifyToken(Request $request)
    {
        $token = $request->header('Authorization');
        $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
        return response()->json(['message' => 'Token verified', 'data' => $decoded], 200);
    }
}
