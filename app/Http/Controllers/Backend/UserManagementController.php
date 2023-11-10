<?php

namespace App\Http\Controllers\Backend;

use App\Actions\MailSendAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function index()
    {
        $data = User::where('type', 2)->latest()->get();
        return view('backend.user-management.institute-admin.list', ['data' => $data]);
    }


    public function othersIndex()
    {
        $data = User::where('type', '>', 2)->latest()->get();
        return view('backend.user-management.others.list', ['data' => $data]);
    }

    public function store (Request $request) {
        $id = 0;
        $model = null;
        if (!empty($request->id)) {
            $id = $request->id;
            $model = User::find($id);
        }

        $data = $request->validate([
            'name'  => 'required',
            // 'username' => 'required',
            'email'  => 'required|email|unique:users,email,'.$id,
            'mobile_no'  => 'required|unique:users,mobile_no,'.$id,
            'password' => 'required_if:' .$id. ',==,0',
            'mobile_no' => 'required',
        ]);

        $all_data = $request->all();
        $all_data['type'] = $request->type ? $request->type : 2;
        $all_data['username'] = $request->email;
  
        if (!$id) {
            $all_data['password'] = Hash::make($data['password']);
            $all_data['email_verified_at'] = Str::random(32);;
        } else {
            unset($all_data['password']);
        }

        try {
            DB::beginTransaction();

            if ($id) {
                $data = $model->update($all_data);
            } else {
                $data = User::create($all_data);
                /** Mail Send to Teachers email */
                (new MailSendAction())->handle($data, 'mail.verification');
            }

            DB::commit();
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

        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => $ex->getMessage()
            ]);
        }
    }


    public function changeStatus(Request $request){

        $model = User::find($request->id);
        
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
