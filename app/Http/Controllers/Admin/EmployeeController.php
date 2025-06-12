<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        return Inertia::render('Dashboard/Employee/Employee',[
            'positions' => Position::where('company_id', $company_id)->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'firstname' => 'required',
            'lastname' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'position_id' => 'required',
            'hired_date' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
            'dob' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
            'password' => 'required|min:8',
            'confirmation_password' => 'required|min:8|same:password',
        ]);

        if($validated->fails()){
            return redirect()->back()->withErrors($validated->errors())->withInput();
        }

        $filename = time().'.'.$request->photo->getClientOriginalExtension();
        $request->photo->move(public_path('images/employee'),$filename);

        $employee = new User();
        $employee->firstname = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->gender = $request->gender;
        $employee->email = $request->email;
        $employee->password = Hash::make($request->password);
        $employee->position_id = $request->position_id;
        $employee->hire_date = $request->hired_date;
        $employee->photo = $filename;
        $employee->dob = $request->dob;
        $employee->phone_number = $request->phone_number;
        $employee->address = $request->address;
        $employee->save();
        $employee->assignRole($request->role);

        return redirect()->route('employee.index')->with('success','Employee created successfully');
    }
}
