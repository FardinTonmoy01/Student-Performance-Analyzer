<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AssignClassTeacher;
use App\Models\MasterGroup;
use App\Models\MasterSection;
use App\Models\MasterSubject;
use App\Models\Teacher;
use App\Services\CommonDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ClassTeacherController extends Controller
{
    public function index()
    {
        
        $insId = session('institute_id') ? session('institute_id') : null;
        $teacherId = session('teacher_id') ? session('teacher_id') : null;

        $data = AssignClassTeacher::with('institute:id,inst_name')
        ->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->when($teacherId, function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })

        ->orderBy('year', 'desc')->get();

        return view('backend.class-teacher.list', ['data' => $data]);
    }

    public function add () {
        $insId = session('institute_id') ? session('institute_id') : null;
        $instituteList = CommonDataService::instituteList();
        $teacherList = CommonDataService::teacherList();
        $classList = CommonDataService::classList();
        $shiftList = CommonDataService::shiftList();

        $groupList = MasterGroup::when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->get();

        $sectionList = MasterSection::when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->get();

        $subjectList = MasterSubject::when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->get();


        return view('backend.class-teacher.add', [
            'instituteList' => $instituteList,
            'teacherList' => $teacherList,
            'classList' => $classList,
            'shiftList' => $shiftList,
            'groupList' => $groupList,
            'sectionList' => $sectionList,
            'subjectList' => $subjectList,
            'institute_id' => $insId ?? 0
        ]);
    }

    // Product Edit 
    public function edit ($id) {
        if ($id) {
            $data = AssignClassTeacher::find($id);
            if ($data) {
                $insId = session('institute_id') ? session('institute_id') : null;
                $instituteList = CommonDataService::instituteList();
                $teacherList = CommonDataService::teacherList();
                $classList = CommonDataService::classList();
                $shiftList = CommonDataService::shiftList();

                $groupList = MasterGroup::when($insId, function ($q) use ($insId) {
                    $q->where('institute_id', $insId);
                })
                ->get();

                $sectionList = MasterSection::when($insId, function ($q) use ($insId) {
                    $q->where('institute_id', $insId);
                })
                ->get();

                $subjectList = MasterSubject::when($insId, function ($q) use ($insId) {
                    $q->where('institute_id', $insId);
                })
                ->get();

                return view('backend.class-teacher.edit', [
                    'data' => $data,
                    'instituteList' => $instituteList,
                    'teacherList' => $teacherList,
                    'classList' => $classList,
                    'shiftList' => $shiftList,
                    'groupList' => $groupList,
                    'sectionList' => $sectionList,
                    'subjectList' => $subjectList,
                    'institute_id' => $insId ?? 0
                ]);

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
            $model = AssignClassTeacher::find($id);
        }

        $request->validate([
            'year'  => 'required|integer',
            'institute_id'  => 'required',
        ]);

        try {
            DB::beginTransaction();
            $all_data = $request->all();

            if ($id) {
                $model->update($all_data);
            } else {
                AssignClassTeacher::create($all_data);
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
