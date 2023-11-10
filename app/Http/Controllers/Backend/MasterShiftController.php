<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterShift;
use App\Services\CommonDataService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterShiftController extends Controller
{
    public function index()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        $data = MasterShift::orderBy('id', 'asc')->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })->get();

        $instituteList = CommonDataService::instituteList();

        return view('backend.master-shift.list', ['data' => $data, 'instituteList' => $instituteList, 'institute_id' => $insId ?? 0]);
    }

    public function store (Request $request) {
        $id = 0;
        $model = null;

        if (!empty($request->id)) {
            $id = $request->id;
            $model = MasterShift::find($id);
        }

        $request->validate([
            'institute_id'  => 'required|integer',
            'shift_name' => [
                'required',
                 Rule::unique('shifts')->where(function ($q) use ($request, $id) {
                    $q->where('institute_id', $request->institute_id);
                    if ($id) {
                        $q =$q->where('id', '!=' ,$id);
                    }
                    return $q;
                 }),
            ]
        ]);

        $all_data = $request->all();

        if ($id) {
            $data = $model->update($all_data);
        } else {
            $data = MasterShift::create($all_data);
        }

        if ($data) {
            return response()->json([
                'success' => true,
                'message' => $id ? 'Updated Successfully.' : 'Added Successfully.',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong! Please Try Again.'
            ], 500);
        }
    }


    public function changeStatus(Request $request){

        $model = MasterShift::find($request->id);
        
        $model->status = $model->status == 1 ? 2 : 1;
        $model->update();

        if($model){
            return response()->json([
                'msg'=>'success'
            ],200);
        }else{
            return response()->json([
                'error'=>'error'
            ],500);
        }
    }
}
