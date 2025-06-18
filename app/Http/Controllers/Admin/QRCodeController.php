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
    private $key = 'c3091290f725c64d0dc3760babf4ed63d32a933e0e13f64925f8162e8c3224ec92e2b63083cac874e46ceba2dba31a780d34b3aaca05e6014faa842fd71127d62a033a61cbcc494857ce49724d281f2bbe76b44a9c07b06e07960159db96d2de83a297562366cabf6d9133477c20fb2fa93d455590999689a2be10cd0bbeb052439a1160a85c5ce1442e3ea81f0cedacf53f08165dc59a04e2894d5b4f14401392c9cd9dced75743eda2bde6ac3daaa652b991f001886746d76232067ed2d85599e9f7985a107d4b968712a441389ffc32cfdad1e4651706de5972d4a33e10e25c3aa55bbd939366d501f8ef2e3e28916f1127e1a4165478c5f6d00ac0d35c91';
    public function showQRCode(Request $request)
    {

        $qrcode = QRCode::with(['company:id,name'])->latest()->paginate(8);
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
        $request->validate([
            'id' => 'required|exists:qrcode,id',
            'image' => 'required|mimes:png'
        ]);

        $new_filename = time().'.'.$request->file('image')->getClientOriginalExtension();
        if($request->has('old_image')){ // mean update
            if (file_exists( public_path() . '/images/qrcode/'.$request->get('old_image'))) {
                unlink(public_path() . '/images/qrcode/'.$request->get('old_image'));
            }
        }

        $request->file('image')->move(public_path('images/qrcode'), $new_filename);
        $qrcode = QRCode::find($request->id);
        $qrcode->image = $new_filename;
        $qrcode->save();

        return redirect()->back()->with('success', 'QR Code Image Uploaded Successfully');
    }

    public function save(Request $request){
        $company_id = Auth::user()->company_id;
        $request->validate([
            'name' => 'required|string|unique:qrcode,name',
            'checkin' => 'required|string',
            'checkout' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'embed_url' => 'required|string',
        ]);

        $payload = [
            'company_id' => $company_id,
            'checkin_time' => $request->checkin,
            'checkout_time' => $request->checkout,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        $token = JWT::encode($payload, $this->key, 'HS256');
        $data = [
            "token" => $token,
            "name" => $request->name,
            "checkin_time" => $request->checkin,
            "checkout_time" => $request->checkout,
            "embed_url" => $request->embed_url ,
            "latitude" => $request->get('latitude'),
            "longitude" => $request->get('longitude'),
            "company_id" => $company_id,"image" => null
        ];
        QRCode::create($data);

        return redirect()->back()->with('success', $token);
    }

    public function update(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $request->validate([
            'id' => 'required|exists:qrcode,id',
            'name' => 'required|string|unique:qrcode,name,'.$request->id,
            'checkin' => 'required|string',
            'checkout' => 'required|string',
            'embed_url' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $qrcode = QRCode::findOrFail($request->id);
        if($qrcode){
            $qrcode->name = $request->name;
            $qrcode->checkin_time = $request->checkin;
            $qrcode->checkout_time = $request->checkout;
            $qrcode->latitude = $request->latitude;
            $qrcode->longitude = $request->longitude;

            $payload = [
                'company_id' => $company_id,
                'checkin_time' => $request->checkin,
                'checkout_time' => $request->checkout,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ];

            $token = JWT::encode($payload, $this->key, 'HS256');
            $qrcode->token = $token;
            $qrcode->embed_url = $request->embed_url;
            $qrcode->save();
            return redirect()->back()->with('success', 'QR Code Updated Successfully');
        }else{
            return redirect()->back()->with('error', 'QR Code Not Found');
        }
    }

    public function delete(Request $request){
        $id = intval($request->id);
        $qrcode = QRCode::find($id);
        $image_name = $qrcode->image;
        if($image_name){
            unlink(public_path('images/qrcode/'.$image_name));
        }
        $qrcode->delete();
        return redirect()->back()->with('success','QR Code deleted Successfully');
    }

}
