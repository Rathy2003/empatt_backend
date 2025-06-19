<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Overtime;
use App\Models\User;
use App\Notifications\NewOvertimeRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequestOvertimeController extends Controller
{
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'employee_id' => 'required:exists:users,id',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'reason' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([$validation->errors()]);
        }

        $data = [
            'employee_id' => $request->get('employee_id'),
            'request_time' => $request->get('date'),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
            'reason' => $request->get('reason'),
        ];

        $overtime =  Overtime::create($data);
        $overtime->load('user');
        $user = User::where('id',$request->employee_id)->first();
        $admins = User::where('company_id',$user->company_id)->role(['admin','ceo'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewOvertimeRequestNotification($overtime));
        }
        return response()->json(["message" => "Your request has been submitted"],201);
    }
}
