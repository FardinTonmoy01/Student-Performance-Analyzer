<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AssignClassTeacher;
use App\Models\MarksEntry;
use App\Models\MarksSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\Rule;

class MarksEntryController extends Controller
{
    public function index()
    {
        $insId = session('institute_id') ? session('institute_id') : null;

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

        $data = MarksEntry::query()
        ->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
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

        return view('backend.marks-entry.list', ['data' => $data]);
    }

    public function addExcel () {
        return view('backend.marks-entry.excel');
    }

    public function add () {
        return view('backend.marks-entry.add');
    }

    // Edit 
    public function edit ($id) {
        if ($id) {
            return view('backend.marks-entry.edit');
        }
        Alert::error('Something went wrong!', 'Please Try Again.');
        return redirect()->back();
    }

    // Edit 
    public function view ($id) {
        if ($id) {
            $data = MarksEntry::where('id', $id)
            ->with('details')
            ->first();

            return view('backend.marks-entry.details', [ 'data' => $data ]);
        }
        Alert::error('Something went wrong!', 'Please Try Again.');
        return redirect()->back();
    }

    
    public function show ($id) {
        if ($id) {
            $data = MarksEntry::where('id', $id)
            ->with('details')
            ->first();

            return response()->json($data, 200);
        }
        return response()->json([
            'message' => 'Not Found'
        ], 404);
    }

    public function store (Request $request) {
        $id = 0;
        $model = null;

        if (!empty($request->id)) {
            $id = $request->id;
            $model = MarksEntry::find($id);
        }

        $request->validate([
            'institute_id'  => 'required',
            'class_id'  => 'required',
            'year' => 'required',
            'section_id' => 'required',
            'shift_id' => 'required',
            'indicator_id' => [
                'required',
                 Rule::unique('marks_entries')->where(function ($q) use ($request, $id) {
                     $q->where('year', $request->year)
                    ->where('institute_id', $request->institute_id)
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
            $details = $request->details;

            if ($id) {
                $model->update($all_data);

                $model->details()->delete();
                $model->details()->createMany($details);
            } else {
                $settings = MarksEntry::create($all_data);
                $settings->details()->createMany($details);
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

        $model = MarksEntry::find($request->id);
        
        $model->status = 1;
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
