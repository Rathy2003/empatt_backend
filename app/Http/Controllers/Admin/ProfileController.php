<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\CheckNameExist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function showAccountInformation()
    {

//        'roles' => Auth::user()->getRoleNames(), // returns a collection of role names
//        'permissions' => Auth::user()->getAllPermissions()->pluck('name'),
        $user = Auth::user();
        return Inertia::render('Dashboard/Profile/AccountInformation',[
            'user' => $user,
            'message' => "test"
        ]);
    }

    public function saveInformation(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'firstname' => 'required',
            'lastname' => [
                'required',
                new CheckNameExist($request->id,$request->firstname,$request->lastname)
            ],
            'email' => 'required|email|unique:users,email,'.Auth::id(),
            'phone_number' => 'required',
            'dob' => 'required|date',
        ]);

        $user = User::find($request->id);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->dob = $request->dob;
        $user->bio = $request->bio;
        $user->phone_number = $request->phone_number;
        $user->save();
        return redirect()->back()->with('success', 'Information updated successfully');

    }
}
