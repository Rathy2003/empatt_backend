<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Overtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RequestOvertimeController extends Controller
{
    public function index(Request $request){
        $companyId = Auth::user()->company_id;
        $search = $request->query('search');
        $overtimes = Overtime::with('employee:id,firstname,lastname')->whereHas('employee', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->orderBy('id', 'desc')->paginate(8);

        return Inertia::render('Dashboard/Overtime/Overtime',[
            'overtimes' => $overtimes
        ]);
    }

    public function updateStatus(Request $request)
    {
        $overtime = Overtime::find($request->id);
        $overtime->status = $request->status;
        $overtime->save();

        return redirect()->back()->with('success', 'Status updated successfully');
    }
}
