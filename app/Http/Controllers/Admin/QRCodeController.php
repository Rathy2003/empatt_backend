<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QRCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class QRCodeController extends Controller
{
    public function showQRCode(Request $request)
    {

        $qrcode = QRCode::latest()->paginate(8);
        $company_id = Auth::user()->company_id;
        $search = $request->query('search');
        if($search){

//            $users = User::with(['company:id,name'])
//                ->where('id', '!=', 1)
//                ->when($search, function ($query, $search) {
//                    $query->where(function ($q) use ($search) {
//                        $q->whereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ["%{$search}%"])
//                            ->orWhere('email', 'like', "%{$search}%")
//                            ->orWhere('phone_number', 'like', "%{$search}%");
//                    });
//                })
//                ->paginate(8)
//                ->appends(['search' => $search]);
        }

        return Inertia::render('Dashboard/QRCode/QRCode',[
            'qrcode' => $qrcode,
            'company_id' => $company_id,
            'filters' => ['search' => $search]
        ]);
    }

    public function saveQRCodeImage(Request $request)
    {
        if($request->hasFile('file')){
            $newfilename = time().'.'.$request->file('file')->getClientOriginalExtension();
            $request->file('file')->move(public_path('images/qrcode'), $newfilename);
        }
    }

    public function save(Request $request){

        $request->validate([
            'name' => 'required|string|unique:qrcode,name',
            'company_id' => 'required|exists:company,id',
        ]);

        $key = 'c3091290f725c64d0dc3760babf4ed63d32a933e0e13f64925f8162e8c3224ec92e2b63083cac874e46ceba2dba31a780d34b3aaca05e6014faa842fd71127d62a033a61cbcc494857ce49724d281f2bbe76b44a9c07b06e07960159db96d2de83a297562366cabf6d9133477c20fb2fa93d455590999689a2be10cd0bbeb052439a1160a85c5ce1442e3ea81f0cedacf53f08165dc59a04e2894d5b4f14401392c9cd9dced75743eda2bde6ac3daaa652b991f001886746d76232067ed2d85599e9f7985a107d4b968712a441389ffc32cfdad1e4651706de5972d4a33e10e25c3aa55bbd939366d501f8ef2e3e28916f1127e1a4165478c5f6d00ac0d35c91';
        $payload = [
            'company_id' => $request->company_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        $token = JWT::encode($payload, $key, 'HS256');
        $data = ["token" => $token,"name" => $request->name, "company_id" => $request->company_id];
        QRCode::create($data);

        return redirect()->back()->with('success', $token);
    }

}
