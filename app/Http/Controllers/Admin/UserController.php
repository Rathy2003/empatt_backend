<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    public function showUser(Request $request)
    {

        $users = User::with(['company:id,name','roles:id,name'])->where('id', '!=', 1)->role(['admin','ceo'])->latest()->paginate(8);
        $search = $request->query('search');
        $companies = Company::all();
        if($search){
            $users = User::with(['company:id,name','roles:id,name'])
                ->where('id', '!=', 1)
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->whereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ["%{$search}%"])
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%");
                    });
                })
                ->paginate(8)
                ->appends(['search' => $search]);
        }

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
            'phone_number'=>'required|string|unique:users,phone_number',
            'company_id'=>'required|numeric|exists:company,id',
            'gender' => 'required|string',
            'password' => 'required|min:8',
            'status' => 'required|string:active,inactive',
            'confirm_password' => 'required|same:password',
            'role' => 'required|string'
        ]);

        $filename = null;
        if($request->photo){
            $filename = $request->photo->getClientOriginalName();
            $filename =  time().$filename;
            $request->photo->move(public_path('images/users'), $filename);
        }

        // allowed role
        $allowed_roles = ['ceo','admin','employee'];
        if(!in_array($request->role, $allowed_roles)){
            return redirect()->back()->with('error','Invalid role');
        }

        $data = $request->only(['firstname','lastname','email','phone_number','company_id','gender','password','role','status']);
        $user = new User();
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->email = $data['email'];
        $user->phone_number = $data['phone_number'];
        $user->company_id = $data['company_id'];
        $user->gender = $data['gender'];
        $user->status = $data['status'];
        $user->password = Hash::make($data['password']);
        if($filename){
            $user->photo = $filename;
        }
        $user->save();

        // assign role
        $user->assignRole($data['role']);
        return redirect()->back()->with('success','User has been created');
    }

    public function update(Request $request){
        $request->validate([
            'id' => 'required|numeric|exists:users,id',
            'firstname'=>'required|string',
            'lastname' => [
                'required',
                'string',
                Rule::unique('users')
                    ->where(function ($query) use ($request) {
                        return $query->where('firstname', $request->firstname);
                    })
                    ->ignore($request->id),
            ],
            'email'=>'required|email|unique:users,email,'.$request->id,
            'phone_number'=>'required|string|unique:users,phone_number,'.$request->id,
            'company_id'=>'required|numeric|exists:company,id',
            'gender' => 'required|string',
            'role' => 'required|string',
            'status' => 'required|string:active,inactive',
        ]);

        // allowed role
        $allowed_roles = ['ceo','admin','employee'];
        if(!in_array($request->role, $allowed_roles)){
            return redirect()->back()->with('error','Invalid role');
        }

        $old_photo = $request->old_photo;

        if($request->photo && $request->photo != $old_photo){
            if(!$old_photo){
                $filename = $request->photo->getClientOriginalName();
                $filename =  time().$filename;
                $old_photo = $filename;
            }
            $request->photo->move(public_path('images/users'), $old_photo);
        }

        $data = $request->only(['firstname','lastname','email','phone_number','company_id','gender','role','status']);
        $user = User::find($request->id);
        if($old_photo){
            $user->photo = $old_photo;
        }
        $user->update($data);
        $user->syncRoles([$data['role']]);

        return redirect()->back()->with('success','Photo has been updated');
    }

    public function delete(Request $request){
        $id = intval($request->id);
        User::destroy($id);
        return redirect()->back()->with('success','User has been deleted');
    }
}