<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Position;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $positions = Position::where('company_id', $company_id)->paginate(5);
        return Inertia::render('Dashboard/Position/Position',[
            'positions' => $positions
        ]);
    }

    public function store(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $validator = Validator::make($request->all(), [
           'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('position')->where(function ($query) use ($company_id) {
                    return $query->where('company_id', $company_id);
                }),
            ],
            'description' => 'nullable|string',
        ]);

       

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Position::create([
            'name' => $request->name,
            'description' => $request->description,
            'company_id' => $company_id,
        ]);

        return redirect()->route('position.index')->with('success', 'Position created successfully');
    }

    public function update(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $id = $request->id;
        
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('position')->ignore($id)->where(function ($query) use ($company_id) {
                    return $query->where('company_id', $company_id);
                }),
            ],
            'description' => 'nullable|string',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Position::where('id', $id)->update($request->only(['name', 'description']));

        return redirect()->route('position.index')->with('success', 'Position updated successfully');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        Position::where('id', $id)->delete();

        return redirect()->route('position.index')->with('success', 'Position deleted successfully');
    }
}
