<?php

namespace App\Http\Controllers\Backend;

use App\Actions\MailSendAction;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Teacher;
use App\Models\User;
use App\Services\CommonDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ParentsController extends Controller
{
    public function index()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        $guardianId = session('guardian_id') ? session('guardian_id') : null;

        $data = Guardian::query()
        ->with('child')
        ->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->when($guardianId, function ($q) use ($guardianId) {
            $q->where('id', $guardianId);
        })
        ->orderBy('id', 'desc')->get();


        return view('backend.parents.list', ['data' => $data]);
    }

    public function add () {
        $insId = session('institute_id') ? session('institute_id') : null;
        $instituteList = CommonDataService::instituteList();
        $studentList = CommonDataService::studentList();
        $classList = CommonDataService::classList();

        return view('backend.parents.add', [
            'instituteList' => $instituteList,
            'studentList' => $studentList,
            'classList' => $classList,
            'institute_id' => $insId ?? 0
        ]);
    }

    // Product Edit 
    public function edit ($id) {
        if ($id) {
            $data = Teacher::find($id);
            if ($data) {
                $instituteList = CommonDataService::instituteList();
                return view('backend.parents.edit', [ 'data' => $data, 'instituteList' => $instituteList ]);
            }
        }
        Alert::error('Something went wrong!', 'Please Try Again.');
        return redirect()->back();
    }

    public function store (Request $request) {
        $id = 0;
        $userId = 0;
        $model = null;

        if (!empty($request->id)) {
            $id = $request->id;
            $model = Guardian::find($id);
            $userId = $model->user_id;
        }

        // $request->validate([
        //     'year'  => 'required|integer',
        //     'institute_id'  => 'required',
        //     'name'  => 'required',
        //     'dob' => 'required',
        //     'gender' => 'required',
        //     'nid' => 'required|unique:teachers,nid,'.$id,
        //     'address' => 'required',
        //     'per_address' => 'required',
        //     'mobile_no'  => 'required|unique:users,mobile_no,'.$userId,
        //     'email'  => 'required|unique:users,email,'.$userId,
        //     'photo'  =>  'mimes:jpeg,jpg,png|max:1028'
        // ]);

        try {
            DB::beginTransaction();
            $all_data = $request->all();
            $path = 'profile/';

            if (request()->file('photo')) {
                $oldfile =  $model ? $model->user->photo : null;
                $fileUp = FileUploadHelper::global_file_upload(request(), 'photo', $path, $oldfile);
                $all_data['photo'] = $fileUp['success'] ? $fileUp['data'] : null;
            } else {
                $all_data['photo'] = $model ? $model->user->photo :  null;
            }

            if ($id) {
                $user = $this->createUser($all_data, $userId);
                $model->update($all_data);
            } else {
                $user = $this->createUser($all_data);
                $all_data['user_id'] = $user->id;
                $guardian = Guardian::create($all_data);

                if ($guardian) {
                    /** Mail Send to Teachers email */
                    (new MailSendAction())->handle($user, 'mail.verification');
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $id ? 'Updated Successfully.' : 'Added Successfully.'
            ], 200);

        } catch (\Exception $ex) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => $ex->getMessage()
            ]);
        }
    }

    public function createUser ($data, $userId = 0)
    {
        if ($userId) {
            $exist = User::find($userId);
            $exist->name = $data['name'];
            $exist->email = $data['email'];
            $exist->username = $data['email'];
            $exist->address = $data['address'];
            $exist->photo = $data['photo'] ?? $exist->photo;
            $exist->mobile_no = $data['mobile_no'];
            $exist->update();

            return $exist;
        }
        
        $userData['name'] = $data['name'];
        $userData['email'] = $data['email'];
        $userData['username'] = $data['email'];
        $userData['type'] = 4; // 3 means teacher
        $userData['password'] = Hash::make('123456');
        $userData['mobile_no'] = $data['mobile_no'];
        $userData['address'] = $data['address'];
        $userData['photo'] = $data['photo'] ?? null;
        $userData['email_verified_at'] = Str::random(32);
        $user = User::create($userData);
        return $user;
    }


    public function changeStatus(Request $request){

        $model = Guardian::find($request->id);
        
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
