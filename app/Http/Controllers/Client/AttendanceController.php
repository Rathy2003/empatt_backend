<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\QRCode;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{

    public function __construct()
    {
        date_default_timezone_set(config('app.timezone')); // <-- Critical
        Carbon::setLocale(app()->getLocale());
    }

    private $key = 'c3091290f725c64d0dc3760babf4ed63d32a933e0e13f64925f8162e8c3224ec92e2b63083cac874e46ceba2dba31a780d34b3aaca05e6014faa842fd71127d62a033a61cbcc494857ce49724d281f2bbe76b44a9c07b06e07960159db96d2de83a297562366cabf6d9133477c20fb2fa93d455590999689a2be10cd0bbeb052439a1160a85c5ce1442e3ea81f0cedacf53f08165dc59a04e2894d5b4f14401392c9cd9dced75743eda2bde6ac3daaa652b991f001886746d76232067ed2d85599e9f7985a107d4b968712a441389ffc32cfdad1e4651706de5972d4a33e10e25c3aa55bbd939366d501f8ef2e3e28916f1127e1a4165478c5f6d00ac0d35c91';

    public function check(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'token' => 'required|exists:qrcode,token',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => 'Missing or invalid data.'], 400);
        }

        try {
            $token = $request->token;
            $userLat = $request->latitude;
            $userLng = $request->longitude;

            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            $expectedLat = $decoded->latitude;
            $expectedLng = $decoded->longitude;

            $now = Carbon::now();

            $periods = [
                'morning_checkin' => $decoded->morning_checkin ?? null,
                'morning_checkout' => $decoded->morning_checkout ?? null,
                'afternoon_checkin' => $decoded->afternoon_checkin ?? null,
                'afternoon_checkout' => $decoded->afternoon_checkout ?? null,
            ];

            $matchedPeriod = null;
            foreach ($periods as $key => $timeStr) {
                if (!$timeStr) continue;

                try {
                    $scanTime = Carbon::createFromFormat('h:i a', $timeStr)->setDateFrom($now);
                } catch (\Exception $e) {
                    Log::error("Time parsing error: {$timeStr}");
                    continue;
                }

                if (Str::endsWith($key, 'checkin')) {
                    $start = $scanTime->copy()->subMinutes(20);
                    $end = $scanTime->copy()->addMinutes(20);
                } else {
                    [$periodBase, $type] = explode('_', $key);
                    $latestCheckin = Attendance::whereDate('created_at', $now->toDateString())
                        ->where('employee_id', $request->user_id)
                        ->where('period', $periodBase)
                        ->where('status', 'checkin')
                        ->first();

                    if (!$latestCheckin) {
                        continue; // can't check out if never checked in
                    }

                    $start = Carbon::parse($latestCheckin->scan_at);
                    $end = $scanTime->copy()->addMinutes(20); // Scheduled checkout + buffer
                }

                if ($now->between($start, $end)) {
                    $matchedPeriod = $key;
                    break;
                }
            }

            if (!$matchedPeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not within any valid attendance scan window.',
                ], 403);
            }

            [$period, $status] = explode('_', $matchedPeriod);

            $alreadyScanned = Attendance::whereDate('created_at', Carbon::today())
                ->where('employee_id', $request->user_id)
                ->where('period', $period)
                ->where('status', $status)
                ->exists();

            if ($alreadyScanned) {
                return response()->json([
                    'success' => false,
                    'message' => "You have already {$status}ed for the {$period} today.",
                ], 400);
            }

            $distance = $this->calculateDistance($userLat, $userLng, $expectedLat, $expectedLng);
            if ($distance > 50) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too far from expected location.',
                    'distance' => round($distance, 2),
                ]);
            }

            if (in_array($matchedPeriod, ['afternoon_checkin', 'afternoon_checkout'])) {
                $morningPeriods = ['checkin', 'checkout'];
                foreach ($morningPeriods as $morningStatus) {
                    $exists = Attendance::whereDate('created_at', Carbon::today())
                        ->where('employee_id', $request->user_id)
                        ->where('period', 'morning')
                        ->where('status', $morningStatus)
                        ->exists();

                    if (!$exists) {
                        Attendance::create([
                            'employee_id' => $request->user_id,
                            'scan_at' => now(),
                            'period' => 'morning',
                            'status' => $morningStatus,
                            'present' => 0,
                            'absent' => 1,
                            'is_late' => 0,
                        ]);
                    }
                }
            }
            $isLate = 0;
            if ($status === 'checkin') {
                $grace = 10; // 10 minutes grace period
                $scheduled = Carbon::createFromFormat('h:i a', $periods["{$period}_{$status}"])->setDateFrom($now);
                $latestAllowed = $scheduled->copy()->addMinutes($grace);
                if ($now->gt($latestAllowed)) {
                    $isLate = 1;
                }
            }

            Attendance::create([
                'employee_id' => $request->user_id,
                'scan_at' => now(),
                'period' => $period,
                'status' => $status,
                'present' => 1,
                'absent' => 0,
                'is_late' => $isLate,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully {$status}ed for the {$period}.",
                'distance' => round($distance, 2),
            ]);

        } catch (\Exception $e) {
            Log::error('Attendance error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token.',
                'error' => $e->getMessage(),
            ], 401);
        }
    }
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
