<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Company;


class DashboardController extends Controller
{
    public function index()
    {

        if(auth()->user()->getRoleNames()->first() === 'admin'){

        }elseif(auth()->user()->getRoleNames()->first() === 'developer'){
            $totalEmployees =User::where('id',"!=",auth()->user()->id)->get()->count();
            $totalCompanies = Company::get()->count();

            return Inertia::render('Dashboard/Dashboard',[
                'totalEmployees' => $totalEmployees,
                'totalCompanies' => $totalCompanies,
                'auth' => auth()->user(),
                'role' => auth()->user()->getRoleNames()->first(),
            ]);
        }

        return Inertia::render('Dashboard/Dashboard',[
            'auth' => auth()->user(),
            'role' => auth()->user()->getRoleNames()->first(),
        ]);
    }
}
