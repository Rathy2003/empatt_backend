<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Overtime;
use App\Models\RequestLeave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Company;


class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->getRoleNames()->first();

        if ($role === 'ceo') {
            $companyId = auth()->user()->company_id; // or pass via request param

            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();

            // Get employee IDs in the company
            $employeeIds = User::where('company_id', $companyId)->pluck('id');

            $attendances = Attendance::whereBetween('created_at', [$start, $end])
                ->whereIn('employee_id', $employeeIds)
                ->get();

            $totalCheckin = Attendance::where('status', 'checkin')
                ->whereIn('employee_id', $employeeIds)
                ->count();

            $totalCheckOut = Attendance::where('status', 'checkout')
                ->whereIn('employee_id', $employeeIds)
                ->count();

            $totalPresent = $attendances->where('present', 1)->count();
            $totalAbsent = $attendances->where('present', 0)->count();
            $totalLate = $attendances->where('is_late', 1)->count();

            $onLeave = RequestLeave::whereIn('employee_id', $employeeIds)->count();
            $totalRequestLeavePending = RequestLeave::whereIn('employee_id', $employeeIds)
                ->where('status', 'pending')
                ->count();
            $totalRequestOverTimePending = Overtime::whereIn('employee_id', $employeeIds)
                ->where('status', 'pending')
                ->count();

            // On Time CheckIn Stats
            $currentYear = now()->year;
            $currentMonth = now()->month;

            $rawData = Attendance::selectRaw("
                MONTH(created_at) as month,
                COUNT(*) as total_checkins,
                SUM(CASE WHEN is_late = 0 THEN 1 ELSE 0 END) as on_time_checkins
            ")
                ->where('status', 'checkin')
                ->whereYear('created_at', $currentYear)
                ->whereIn('employee_id', $employeeIds)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('on_time_checkins', 'month');

            $monthlyOnTime = collect(range(1, $currentMonth))->map(function ($month) use ($rawData, $employeeIds) {
                $total = Attendance::whereMonth('created_at', $month)
                    ->whereYear('created_at', now()->year)
                    ->where('status', 'checkin')
                    ->whereIn('employee_id', $employeeIds)
                    ->count();

                $onTime = $rawData[$month] ?? 0;
                $percentage = $total > 0 ? round(($onTime / $total) * 100, 2) : 0;

                return [
                    'month' => Carbon::create()->month($month)->format('M'),
                    'performance' => $percentage,
                ];
            });

            // Monthly Approved Overtime Requests
            $overtimeRaw = Overtime::selectRaw('
                MONTH(created_at) as month,
                COUNT(*) as total
            ')
                ->whereYear('created_at', $currentYear)
                ->where('status', 'approved') // ✅ Only approved requests
                ->whereIn('employee_id', $employeeIds)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('total', 'month');

            $monthlyOvertime = collect(range(1, $currentMonth))->map(function ($month) use ($overtimeRaw) {
                return [
                    'month' => Carbon::create()->month($month)->format('M'),
                    'total' => $overtimeRaw[$month] ?? 0,
                ];
            });

            return Inertia::render('Dashboard/Dashboard', [
                'auth' => auth()->user(),
                'role' => $role,
                'summary' => [
                    'present' => $totalPresent,
                    'absent' => $totalAbsent,
                    'late' => $totalLate,
                    'leave' => $onLeave,
                    'checkedIn' => $totalCheckin,
                    'checkedOut' => $totalCheckOut,
                    'totalRequestLeavePending' => $totalRequestLeavePending,
                    'totalOvertimePending' => $totalRequestOverTimePending,
                    'approvedOvertime' => $monthlyOvertime,
                    'onTimeCheckin' => $monthlyOnTime,
                ],
            ]);
        }

        if ($role === 'developer') {
            $totalEmployees = User::where('id', '!=', auth()->user()->id)->count();
            $totalCompanies = Company::count();

            return Inertia::render('Dashboard/Dashboard', [
                'auth' => auth()->user(),
                'role' => $role,
                'totalEmployees' => $totalEmployees,
                'totalCompanies' => $totalCompanies,
            ]);
        }

        return Inertia::render('Dashboard/Dashboard', [
            'auth' => auth()->user(),
            'role' => $role,
        ]);
    }

}
