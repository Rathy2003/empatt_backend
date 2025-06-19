<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ProfileController extends Controller
{

    private $key = 'eyJhbGciOiJIUzI1NiJ9.eyJSb2xlIjoiQWRtaW4iLCJJc3N1ZXIiOiJJc3N1ZXIiLCJVc2VybmFtZSI6IkphdmFJblVzZSIsImV4cCI6MTc0OTcxNDE5NCwiaWF0IjoxNzQ5NzE0MTk0fQ.lhbnaIUiJ_p_xD6YkcdK9tyqkY6tTOeC7k-b13NA0qc';

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => ['required','string','max:255',
                function($attribute, $value, $fail) use ($request) {
                    $user = User::whereRaw('LOWER(firstname) = ?', [strtolower($request->firstname)])
                        ->whereRaw('LOWER(lastname) = ?', [strtolower($value)])
                        ->where('company_id', $request->company_id)
                        ->where('id', '!=', $request->id)
                        ->first();

                    if ($user) {
                        $fail('The firstname and lastname combination has already been taken.');
                    }
                },
            ],
            'phone_number' => 'required|string|unique:users,phone_number,' . $request->id,
            'dob' => 'required|date',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([$validator->errors()], 422);
        }
        $user = User::find($request->id);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->phone_number = $request->phone_number;
        $user->dob = $request->dob;
        $user->address = $request->address;
        $user->save();

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
            'role' => $user->role(),
            'hire_date' => $user->hire_date,
            'company_id' => $user->company_id,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 30)
        ];

        $token = JWT::encode($payload, $this->key, 'HS256');

        return response()->json(['message' => 'Profile updated successfully', 'token' => $token], 200);
    }
}
