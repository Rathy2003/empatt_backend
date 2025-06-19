<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\RequestLeave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $userId = $request->get('user_id');
        $totalPresent = Attendance::where('employee_id', $userId)
            ->where('present', 1)
            ->count();
        $totalAbsent = Attendance::where('employee_id', $userId)
            ->where('present', 0)
            ->count();
        $totalLate = Attendance::where('employee_id', $userId)
            ->where('is_late', 1)
            ->count();

        $onLeave = RequestLeave::where('employee_id', $userId)
            ->whereDate('created_at', '<=', now())
            ->count();

        return response()->json([
            'totalPresent' => $totalPresent,
            'totalAbsent' => $totalAbsent,
            'totalLate' => $totalLate,
            'onLeave' => $onLeave,
        ]);
    }

    public function filterSummary(Request $request)
    {
        $userId = $request->get('user_id');
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        $attendanceQuery = Attendance::where('employee_id', $userId);
        if ($startDate && $endDate) {
            $attendanceQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalPresent = (clone $attendanceQuery)->where('present', 1)->count();
        $totalAbsent = (clone $attendanceQuery)->where('present', 0)->count();
        $totalLate = (clone $attendanceQuery)->where('is_late', 1)->count();

        $leaveQuery = RequestLeave::where('employee_id', $userId);
        if ($startDate && $endDate) {
            $leaveQuery->whereRaw('DATE(created_at) <= ?', [$endDate->toDateString()])
                ->whereRaw('DATE_ADD(DATE(created_at), INTERVAL duration - 1 DAY) >= ?', [$startDate->toDateString()]);
        } else {
            $today = Carbon::today()->toDateString();
            $leaveQuery->whereRaw('DATE(created_at) <= ?', [$today])
                ->whereRaw('DATE_ADD(DATE(created_at), INTERVAL duration - 1 DAY) >= ?', [$today]);
        }
        $onLeave = $leaveQuery->count();

        return response()->json([
            'totalPresent' => $totalPresent,
            'totalAbsent' => $totalAbsent,
            'totalLate' => $totalLate,
            'onLeave' => $onLeave,
        ]);
    }

}
