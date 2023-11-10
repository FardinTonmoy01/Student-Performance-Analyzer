<?php

namespace App\Http\Controllers\Backend;

use App\Actions\MailSendAction;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\AssignClassTeacher;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Services\CommonDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        // institute
        $insId = session('institute_id') ? session('institute_id') : null;
        // guardian
        $guardianId = session('guardian_id') ? session('guardian_id') : null;
        $childId = null;
        if ($guardianId) {
            $childId = session('guardian_child_id') ? session('guardian_child_id') : null;
        }
        // student
        $studentId = session('student_id') ? session('student_id') : null;
        // teacher
        $teacherId = session('teacher_id') ?? null;

        $classIds = null;
        $sectionIds = null;
        $groupIds = null;
        $shiftIds = null;
        if ($teacherId) {
            $classIds = session('class_ids') ?? null;
            // $groupIds = session('group_ids') ?? null;
            $sectionIds = session('section_ids') ?? null;
            $shiftIds = session('shift_ids') ?? null;
        }

        $data = Student::query()
        ->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->when($studentId, function ($q) use ($studentId) {
            $q->where('id', $studentId);
        })
        ->when($childId, function ($q) use ($childId) {
            $q->where('user_id', $childId);
        })
        ->when($classIds, function ($q) use ($classIds) {
            $q->whereIn('class_id', $classIds);
        })
        ->when($groupIds, function ($q) use ($groupIds) {
            $q->whereIn('group_id', $groupIds);
        })
        ->when($sectionIds, function ($q) use ($sectionIds) {
            $q->whereIn('section_id', $sectionIds);
        })
        ->when($shiftIds, function ($q) use ($shiftIds) {
            $q->whereIn('shift_id', $shiftIds);
        })
        ->orderBy('year', 'desc')->get();


        return view('backend.student.list', ['data' => $data]);
    }

    public function add () {
        $insId = session('institute_id') ? session('institute_id') : null;
        $instituteList = CommonDataService::instituteList();
        $classList = CommonDataService::classList();
        $shiftList = CommonDataService::shiftList();
        $groupList = CommonDataService::groupList();
        $sectionList = CommonDataService::sectionList();

        return view('backend.student.add', [
            'instituteList' => $instituteList,
            'classList' => $classList,
            'shiftList' => $shiftList,
            'groupList' => $groupList,
            'sectionList' => $sectionList,
            'institute_id' => $insId ?? 0
        ]);
    }


    public function addExcel () {
        $insId = session('institute_id') ? session('institute_id') : null;
        $instituteList = CommonDataService::instituteList();
        $classList = CommonDataService::classList();
        $shiftList = CommonDataService::shiftList();
        $groupList = CommonDataService::groupList();
        $sectionList = CommonDataService::sectionList();

        return view('backend.student.excel', [
            'instituteList' => $instituteList,
            'classList' => $classList,
            'shiftList' => $shiftList,
            'groupList' => $groupList,
            'sectionList' => $sectionList,
            'institute_id' => $insId ?? 0
        ]);
    }

    // Product Edit 
    public function edit ($id) {
        if ($id) {
            $data = Student::find($id);
            if ($data) {
                $insId = session('institute_id') ? session('institute_id') : null;
                $instituteList = CommonDataService::instituteList();
                $classList = CommonDataService::classList();
                $shiftList = CommonDataService::shiftList();
                $groupList = CommonDataService::groupList();
                $sectionList = CommonDataService::sectionList();

                return view('backend.student.edit', [
                    'data' => $data,
                    'instituteList' => $instituteList,
                    'classList' => $classList,
                    'shiftList' => $shiftList,
                    'groupList' => $groupList,
                    'sectionList' => $sectionList,
                    'institute_id' => $insId ?? 0
                ]);

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
            $model = Student::find($id);
            $userId = $model->user_id;
        }

        $request->validate([
            'year'  => 'required|integer',
            'institute_id'  => 'required',
            'name'  => 'required',
            'dob' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'per_address' => 'required',
            'mobile_no'  => 'required|unique:users,mobile_no,'.$userId,
            'email'  => 'required|unique:users,email,'.$userId,
            'photo'  =>  'mimes:jpeg,jpg,png|max:1028',
            'roll_no' => [
                'required',
                 Rule::unique('students')->where(function ($q) use ($request, $id) {
                    $q->where('institute_id', $request->institute_id)
                    ->where('class_id', $request->class_id)
                    ->where('section_id', $request->section_id)
                    ->where('shift_id', $request->shift_id);

                    if ($request->group_id) {
                        $q->where('group_id', $request->group_id);
                    }

                    if ($id) {
                        $q =$q->where('id', '!=' ,$id);
                    }
                    return $q;
                 }),
            ]
        ]);

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
                $student = Student::create($all_data);

                if ($student) {
                    /** Mail Send to Students email */
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
        $userData['type'] = 5; // 3 means student
        $userData['password'] = Hash::make('123456');
        $userData['mobile_no'] = $data['mobile_no'];
        $userData['address'] = $data['address'];
        $userData['photo'] = $data['photo'] ?? null;
        $userData['email_verified_at'] = Str::random(32);

        $user = User::create($userData);
        return $user;
    }


    public function changeStatus(Request $request){

        $model = Teacher::find($request->id);
        
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
