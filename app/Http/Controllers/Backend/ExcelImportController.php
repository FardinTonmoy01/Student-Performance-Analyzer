<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Imports\StudentImport;
use App\Imports\StudentMarksImport;
use App\Models\MarksEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{
    public function importStudentExcel (Request $request) {
        $request->validate([
            'year'  => 'required|integer',
            'institute_id'  => 'required',
            'class_id'  => 'required',
            'section_id'  => 'required',
            'shift_id'  => 'required',
            'file'  =>  'mimes:xls,xlsx,csv|max:3072',
        ]);

        $file = $request->file;
        $data = $request->all();

        Excel::import(new StudentImport($data), $file);

        return response()->json([
            'success' => true,
            'message' => 'Imported Successfully.'
        ], 200);
    }


    public function importMarksExcel (Request $request)
    {
        $request->validate([
            'institute_id'  => 'required',
            'class_id'  => 'required',
            'year' => 'required',
            'section_id' => 'required',
            'shift_id' => 'required',
            'indicator_id' => 'required',
            'file'  =>  'mimes:xls,xlsx,csv|max:3072',
        ]);


        $subjectList = (new ExcelExportController())->classWiseSubject($request);
        foreach ($subjectList as $key => $item) {
            $marks =  (new ExcelExportController())->subjectWiseSettingsMarks($request, $item['id']);
            if (!$marks->marks) {
                throw new \Exception('This indicator required subject wise marks settings');
            }
        }


        $file = $request->file;
        $data = $request->all();

        try {
            DB::beginTransaction();
            $groupId = $request->group_id ?? null;
            $markss = MarksEntry::where('year', $data['year'])->where('institute_id', $data['institute_id'])->where('class_id', $data['class_id'])
            ->when($groupId, function ($q) use ($groupId) {
                $q->where('group_id', $groupId);
            })
            ->where('section_id', $data['section_id'])
            ->where('shift_id', $data['shift_id'])
            ->where('indicator_id', $data['indicator_id'])
            ->first();

            $id = 0;
            if ($markss) {
                $id = $markss->id;
            } else {
                if ($data['group_id'] == 'null') {
                    $data['group_id'] = null;
                }

                $marksd = MarksEntry::create($data);
                $id = $marksd->id;
            }

            Excel::import(new StudentMarksImport($id), $file);

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Imported Successfully.'
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

}
