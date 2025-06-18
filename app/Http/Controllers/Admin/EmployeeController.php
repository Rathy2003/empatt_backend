<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Position;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $employees = User::where('company_id', $company_id)
            ->role(['employee','admin'])
            ->with('position:id,name','roles:id,name')->paginate(10);
        $search = $request->query('search');
        $role = Auth::user()->getRoleNames()->first();
        if($search){
            $employees = User::with(['position:id,name','roles:id,name'])
            ->role(['employee','admin'])
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
   
       
        return Inertia::render('Dashboard/Employee/Employee',[
            'positions' => Position::where('company_id', $company_id)->get(),
            'employees' => $employees,
            'filters' => ['search' => $search],
            'role' => $role
        ]);
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'firstname' => 'required',
            'lastname' => [
                'required',
                'string',
                Rule::unique('users')
                    ->where(function ($query) use ($request) {
                        return $query->where('firstname', $request->firstname)->where('company_id', Auth::user()->company_id);
                    })
            ],
            'gender' => 'required',
            'email' => 'required|email',
            'position_id' => 'required',
            'hired_date' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5000',
            'dob' => 'required',
            'salary' => 'required|numeric|min:0',
            'phone_number' => 'required|unique:users,phone_number',
            'address' => 'required',
            'password' => 'required|min:8',
            'confirmation_password' => 'required|min:8|same:password',
        ]);

        $role = $request->role ? $request->role : 'employee';

        if($validated->fails()){
            return redirect()->back()->withErrors($validated->errors())->withInput();
        }

        $filename = time().'.'.$request->photo->getClientOriginalExtension();
        $request->photo->move(public_path('images/users'),$filename);

        $employee = new User();
        $employee->firstname = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->gender = $request->gender;
        $employee->email = $request->email;
        $employee->password = Hash::make($request->password);
        $employee->position_id = $request->position_id;
        $employee->hire_date = $request->hired_date;
        $employee->salary = $request->salary;
        $employee->photo = $filename;
        $employee->dob = $request->dob;
        $employee->phone_number = $request->phone_number;
        $employee->address = $request->address;
        $employee->company_id = Auth::user()->company_id;
        $employee->save();
        $employee->assignRole($role);

        return redirect()->route('employee.index')->with('success','Employee created successfully');
    }

    public function update(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'firstname' => 'required',
            'lastname' => [
                'required',
                'string',
                Rule::unique('users')
                    ->where(function ($query) use ($request) {
                        return $query->where('firstname', $request->firstname)->where('company_id', Auth::user()->company_id);
                    })
                    ->ignore($request->id),
            ],
            'gender' => 'required|in:Male,Female',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'position_id' => 'required|exists:position,id',
            'hired_date' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5000', // max size 5MB
            'dob' => 'required|date',
            'salary' => 'required|numeric|min:0',
            'phone_number' => 'required|unique:users,phone_number,'.$request->id,
            'address' => 'required',
            'password' => 'nullable|min:8',
            'confirmation_password' => 'nullable|min:8|same:password',
        ]);

        if($validated->fails()){
            return redirect()->back()->withErrors($validated->errors())->withInput();
        }

        $filename = $request->old_photo;
        if($request->hasFile('photo')){
            $request->photo->move(public_path('images/users'),$filename);
        }

        $role = $request->role ? $request->role : 'employee';
        
        $employee = User::find($request->id);
        $employee->firstname = $request->firstname;
        $employee->lastname = $request->lastname;
        $employee->gender = $request->gender;
        $employee->email = $request->email;
        $employee->position_id = $request->position_id;
        $employee->hire_date = $request->hired_date;
        $employee->salary = $request->salary;
        $employee->photo = $filename;
        $employee->dob = $request->dob;
        $employee->phone_number = $request->phone_number;
        $employee->address = $request->address;
        if($request->password){
            $employee->password = Hash::make($request->password);
        }
        $employee->save();
        $employee->assignRole($role);

        return redirect()->route('employee.index')->with('success','Employee updated successfully');
    }

    public function destroy(Request $request)
    {
        $employee = User::find($request->id);
        $employee->delete();
        return redirect()->route('employee.index')->with('success','Employee deleted successfully');
    }
}
