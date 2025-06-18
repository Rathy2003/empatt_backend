<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    private $key = 'c3091290f725c64d0dc3760babf4ed63d32a933e0e13f64925f8162e8c3224ec92e2b63083cac874e46ceba2dba31a780d34b3aaca05e6014faa842fd71127d62a033a61cbcc494857ce49724d281f2bbe76b44a9c07b06e07960159db96d2de83a297562366cabf6d9133477c20fb2fa93d455590999689a2be10cd0bbeb052439a1160a85c5ce1442e3ea81f0cedacf53f08165dc59a04e2894d5b4f14401392c9cd9dced75743eda2bde6ac3daaa652b991f001886746d76232067ed2d85599e9f7985a107d4b968712a441389ffc32cfdad1e4651706de5972d4a33e10e25c3aa55bbd939366d501f8ef2e3e28916f1127e1a4165478c5f6d00ac0d35c91';
    public function check(Request $request)
    {

        $valiation = Validator::make($request->all(), [
           'user_id' => 'required:exists:users,id',
           'token' => 'required:exists:qrcode,token',
           'latitude' => 'required',
           'longitude' => 'required'
        ]);

        if($valiation->fails()){
            return response()->json(['success' => false, 'message' => 'Missing required data.'], 400);
        }

        $token = $request->token;
        $userLat = $request->input('latitude');
        $userLng = $request->input('longitude');

        // Total Today Scan
        $totalToday = Attendance::whereDate('created_at', Carbon::today())->count();
        if($totalToday >= 2 ){
            return response()->json(['success' => false, 'message' => 'Today attendance is over.'], 400);
        }

        try {
            // Decode token using secret key
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));

            // If your token payload looks like:
            // { "latitude": 11.5, "longitude": 104.8 }
            $expectedLat = $decoded->latitude;
            $expectedLng = $decoded->longitude;

            // Calculate distance in meters
            $distance = $this->calculateDistance($userLat, $userLng, $expectedLat, $expectedLng);

            if ($distance <= 50) {

                $attenance = new Attendance();
                $attenance->employee_id = $request->user_id;
                $attenance->scan_at = Carbon::now()->format('Y-m-d H:i:s');
                $attenance->present = 1;

                $attenance->save();

                return response()->json(['success' => true, 'distance' => round($distance, 2)]);
            } else {
                // ❌ Too far from expected point
                return response()->json([
                    'success' => false,
                    'message' => 'Too far from expected location.',
                    'distance' => round($distance, 2)
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token.',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in meters
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance; // in meters
    }
}
