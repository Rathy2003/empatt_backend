<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function showCompany(Request $request)
    {
        $companies = Company::latest()->paginate(8);
        $search = $request->query('search');
        if($search){
            $companies = Company::query()
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('phone_number', 'like', "%{$search}%");
                    });
                })
                ->paginate(8)
                ->appends(['search' => $search]);
        }


        return Inertia::render('Dashboard/Company/Company',[
            'companies' => $companies,
            'filters' => ['search' => $search]
        ]);
    }

    public function save(Request $request){
        $request->validate([
           'name'=>'required|string|unique:company,name',
           'email'=>'required|email|unique:company,email',
           'phone_number'=>'required|numeric|unique:company,phone_number',
        ]);

        $filename = null;
        if($request->logo){
            $filename = $request->logo->getClientOriginalName();
            $filename =  time().$filename;
            $request->logo->move(public_path('images/logo'), $filename);
        }

        $data = $request->only(['name','email','phone_number']);
        $company = new Company();
        $company->name = $data['name'];
        $company->email = $data['email'];
        $company->phone_number = $data['phone_number'];
        if($filename){
            $company->logo = $filename;
        }

        $company->save();
        return redirect()->back()->with('success','Company has been created');
    }

    public function update(Request $request){
        $request->validate([
            'id' => 'required|numeric|exists:company,id',
            'name'=>'required|string|unique:company,name,'.$request->id,
            'email'=>'required|email|unique:company,email,'.$request->id,
            'phone_number'=>'required|string|unique:company,phone_number,'.$request->id,
        ]);

        $old_logo = $request->old_logo;

        if($request->logo && $request->logo != $old_logo){
            if(!$old_logo){
                $filename = $request->logo->getClientOriginalName();
                $filename =  time().$filename;
                $old_logo = $filename;
            }


            $request->logo->move(public_path('images/logo'), $old_logo);
        }

        $data = $request->only(['name','email','phone_number']);
        $company = Company::find($request->id);
        if($old_logo){
            $company->logo = $old_logo;
        }
        $company->update($data);
        return redirect()->back()->with('success','Company has been updated');
    }


    public function delete(Request $request){
        $id = intval($request->id);
        Company::destroy($id);
        return redirect()->back()->with('success','Company has been deleted');
    }
}
