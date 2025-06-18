<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\RequestLeave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class RequestLeaveController extends Controller
{
    public function index(Request $request){

        $search = $request->query('search');
        $companyId = Auth::user()->company_id;
        $requestLeaves = RequestLeave::with('employee:id,firstname,lastname')->whereHas('employee', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->orderBy('id', 'desc')->paginate(8);

        if($search){
            $requestLeaves = RequestLeave::with('employee:id,firstname,lastname')
                ->whereHas('employee', function ($query) use ($companyId, $search) {
                    $query->where('company_id', $companyId)
                        ->when($search, function ($q) use ($search) {
                            $q->whereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ["%{$search}%"]);
                        });
                })
                ->orderBy('id', 'desc')
                ->paginate(8)
                ->appends(['search' => $search]);
        }

        return Inertia::render('Dashboard/Request/RequestLeave',[
            'requestLeaves' => $requestLeaves,
            'filters' => ['search' => $search],
        ]);
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'employee_id' => 'required:exists:users,id',
            'title' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'reason' => 'required',
        ]);

        if($validation->fails()){
            return response()->json([$validation->errors()],400);
        }

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $duration = $start->diffInDays($end);
        $requestLeave = new RequestLeave();
        $requestLeave->employee_id = $request->employee_id;
        $requestLeave->title = $request->title;
        $requestLeave->duration = $duration;
        $requestLeave->reason = $request->reason;
        $requestLeave->save();

        return response()->json(["message" => "Request has been submitted."],201);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:request,id',
            'status' => 'required|in:accepted,rejected',
        ]);
        $requestLeave = RequestLeave::find($request->id);
        $requestLeave->status = $request->status;
        $requestLeave->save();

        return redirect()->back()->with('message','Request status has been updated.');
    }
}
