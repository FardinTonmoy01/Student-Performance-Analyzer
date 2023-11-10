<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\InstituteInfo;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class InstituteInfoController extends Controller
{
    public function index()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        $data = InstituteInfo::with('admin:id,name')
        ->when($insId, function ($q) use ($insId) {
            $q->where('id', $insId);
        })
        ->orderBy('sorting_order', 'asc')->get();
        return view('backend.institute-info.list', ['data' => $data]);
    }

    public function add () {
        $adminList = User::where('status', 1)->where('type', 2)
        ->orderBy('name', 'ASC')->get();

        return view('backend.institute-info.add', ['adminList' => $adminList]);
    }

    // Product Edit 
    public function edit ($id) {
        if ($id) {
            $data = InstituteInfo::find($id);
            if ($data) {
                $adminList = User::where('type', 2)->orderBy('name', 'ASC')->get();
                return view('backend.institute-info.edit', [ 'data' => $data, 'adminList' => $adminList ]);
            }
        }
        Alert::error('Something went wrong!', 'Please Try Again.');
        return redirect()->back();
    }

    public function store (Request $request) {
        $id = 0;
        $model = null;

        if (!empty($request->id)) {
            $id = $request->id;
            $model = InstituteInfo::find($id);
        }

        $request->validate([
            'admin_id'  => 'required|unique:institute_informations,admin_id,'.$id,
            'inst_name'  => 'required|unique:institute_informations,inst_name,'.$id,
            'sorting_order'  => 'required|unique:institute_informations,sorting_order,'.$id,
            'phone'  => 'required|unique:institute_informations,phone,'.$id,
            'email'  => 'required|unique:institute_informations,email,'.$id,
            'address'  =>  'required',
            'establishment_year'  =>  'required',
            'logo'  =>  'mimes:jpeg,jpg,png|max:1028|required_if:' .$id. ',==,0'
        ]);

        $all_data = $request->all();
        $path = 'institute-info/';

        if (request()->file('logo')) {
            $oldfile =  $model ? $model->logo : null;
            $fileUp = FileUploadHelper::global_file_upload(request(), 'logo', $path, $oldfile);
            $all_data['logo'] = $fileUp['success'] ? $fileUp['data'] : null;
        } else {
            $all_data['logo'] = $model ? $model->logo :  null;
        }

        if ($id) {
            $data = $model->update($all_data);
        } else {
            $data = InstituteInfo::create($all_data);
        }

        if ($data) {
            return response()->json([
                'success' => true,
                'message' => $id ? 'Updated Successfully.' : 'Added Successfully.'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong! Please Try Again.'
            ], 500);
        }
    }


    public function changeStatus(Request $request){

        $model = InstituteInfo::find($request->id);
        
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
