<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function showUser(Request $request)
    {

        $users = User::with(['company:id,name'])->where('id', '!=', 1)->latest()->paginate(8);
        $search = $request->query('search');
        $companies = Company::all();
        if($search){
            $users = User::query()
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('email', 'like', "%{$search}%");
                    });
                })
                ->paginate(8)
                ->appends(['search' => $search]);
        }

//        $companies = User::query()
//            ->when($search, function ($query, $search) {
//                $query->where(function ($q) use ($search) {
//                    $q->where('name', 'like', "%{$search}%")
//                        ->orWhere('email', 'like', "%{$search}%")
//                        ->orWhere('phone_number', 'like', "%{$search}%");
//                });
//            })
//            ->paginate(8)
//            ->appends(['search' => $search]);


        return Inertia::render('Dashboard/User/User',[
            'users' => $users,
            'companies' => $companies,
            'filters' => ['search' => $search]
        ]);
    }

    public function save(Request $request){
        $request->validate([
            'firstname'=>'required|string',
            'lastname'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'phone_number'=>'required|numeric|unique:users,phone_number',
            'company_id'=>'required|numeric|exists:company,id',
            'gender' => 'required|string',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'role' => 'required|string'
        ]);

        $filename = null;
        if($request->photo){
            $filename = $request->photo->getClientOriginalName();
            $filename =  time().$filename;
            $request->photo->move(public_path('images/users'), $filename);
        }

        $data = $request->only(['firstname','lastname','email','phone_number','company_id','gender','password','role']);
        $user = new User();
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->email = $data['email'];
        $user->phone_number = $data['phone_number'];
        $user->company_id = $data['company_id'];
        $user->gender = $data['gender'];
        $user->password = Hash::make($data['password']);
        $user->role = $data['role'];
        if($filename){
            $user->photo = $filename;
        }
        $user->save();

        // assign role
        $user->assignRole($data['role']);
        return redirect()->back()->with('success','User has been created');
    }
}
