<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MarksSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Validation\Rule;

class MarksSettingsController extends Controller
{
    public function index()
    {
        $insId = session('institute_id') ? session('institute_id') : null;

        $data = MarksSetting::query()
        ->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->orderBy('year', 'desc')->get();

        return view('backend.marks-settings.list', ['data' => $data]);
    }

    public function add () {
        return view('backend.marks-settings.add');
    }

    // Edit 
    public function edit ($id) {
        if ($id) {
            return view('backend.marks-settings.edit');
        }
        Alert::error('Something went wrong!', 'Please Try Again.');
        return redirect()->back();
    }


    public function view ($id) {
        if ($id) {

            return view('backend.marks-settings.details');
        }
        Alert::error('Something went wrong!', 'Please Try Again.');
        return redirect()->back();
    }


    public function show ($id) {
        if ($id) {
            $data = MarksSetting::where('id', $id)
            ->with('indicators')
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
            $model = MarksSetting::find($id);
        }

        $request->validate([
            'institute_id'  => 'required',
            'class_id'  => 'required',
            'year' => [
                'required',
                 Rule::unique('marks_settings')->where(function ($q) use ($request, $id) {
                    $q->where('institute_id', $request->institute_id)
                    ->where('class_id', $request->class_id);

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

            $newArr = [];
            foreach ($request->subjects as $key => $item) {
                foreach($item['indicators'] as $key1 => $el) {
                    $el['subject_id'] = $item['id'];
                    $el['indicator_id'] = $id ? $el['indicator_id'] : $el['id'];

                    $newArr[] = $el;
                }
            }

            if ($id) {
                $model->update($all_data);

                $model->indicators()->delete();
                $model->indicators()->createMany($newArr);
            } else {
                $settings = MarksSetting::create($all_data);
                $settings->indicators()->createMany($newArr);
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

        $model = MarksSetting::find($request->id);
        
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
