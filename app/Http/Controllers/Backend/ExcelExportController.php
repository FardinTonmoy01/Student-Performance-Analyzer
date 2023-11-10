<?php

namespace App\Http\Controllers\Backend;

use App\Exports\SubjectWiseStudentExport;
use App\Http\Controllers\Controller;
use App\Models\MarksSettingDetail;
use App\Models\MasterSubject;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExportController extends Controller
{
    public function exportExcel (Request $request) {
        $request->validate([
            'year'  => 'required|integer',
            'institute_id'  => 'required',
            'class_id'  => 'required',
            'section_id'  => 'required',
            'shift_id'  => 'required',
            'indicator_id' => 'required'
        ]);
        $data = $request->all();


        $subjectList = $this->classWiseSubject($request);
        $studentList = $this->classWiseStudent($request);


        foreach ($subjectList as $key => $item) {
            $marks = $this->subjectWiseSettingsMarks($request, $item['id']);
            // dd($marks);
            if (!$marks->marks) {
                throw new \Exception('This indicator required subject wise marks settings');
            }
        }

        $newArr = [];
        foreach ($subjectList as $key => $item) {
            $marks = $this->subjectWiseSettingsMarks($request, $item['id']);
            foreach ($studentList as $key2 => $item2) {
                $newItm['subject_name'] = $item['subject_name'];
                $newItm['student_name'] = $item2['text'];
                $newItm['class'] = $item2['class']['class_name'];
                $newItm['group'] = $item2['group_id'] ? $item2['group']['group_name'] : '';
                $newItm['section'] = $item2['section']['section_name'];
                $newItm['shift'] = $item2['shift']['shift_name'];

                $newItm['subject_id'] = $item['id'];
                $newItm['student_id'] = $item2['id'];
                $newItm['total_marks'] = $marks->marks;
                $newItm['obtain_marks'] = '';

                array_push($newArr, $newItm);
            }
        }

        // return Excel::download(new SubjectWiseStudentExport($data), 'students.xlsx');

        return Excel::download(new SubjectWiseStudentExport($newArr), 'marks.xlsx')->deleteFileAfterSend();

    }


    public function subjectWiseSettingsMarks ($request, $subjectId)
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        $groupId = $request->group_id ?? null;

        $data = MarksSettingDetail::where(['indicator_id' => $request->indicator_id, 'subject_id' => $subjectId])
        ->whereHas('main', function ($q) use ($groupId, $insId, $request) {
            $q->when($insId, function ($p) use ($insId) {
                $p->where('institute_id', $insId);
            })
            ->where('year', $request->year)
            ->where('class_id', $request->class_id)
            ->when($groupId, function ($p) use ($groupId) {
                $p->where('group_id', $groupId);
            })
            ->where('status', 1);
        })
        ->first();

        return $data;
    }


    public function classWiseSubject ($request)
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        // teacher
        $teacherId = session('teacher_id') ?? null;
        $subjectIds = null;
        if ($teacherId) {
            $subjectIds = session('subject_ids') ?? null;
        }

        $groupId = $request->group_id ?? null;

        $data = MasterSubject::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->where('class_id', $request->class_id)
        ->when($groupId, function ($q) use ($groupId) {
            $q->where('group_id', $groupId);
        })
        ->when($subjectIds, function ($q) use ($subjectIds) {
            $q->whereIn('id', $subjectIds);
        })
        ->get();

        return $data;
    }


    public function classWiseStudent ($request)
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        $groupId = $request->group_id ?? null;

        $data = Student::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->where('class_id', $request->class_id)
        ->where('year', $request->year)
        ->when($groupId, function ($q) use ($groupId) {
            $q->where('group_id', $groupId);
        })
        ->where('section_id', $request->section_id)
        ->where('shift_id', $request->shift_id)
        ->get()->map(function ($item) {
            $item->text = $item->user->name .' (Roll-'.$item->roll_no.')';
            return $item;
        });

        return $data;
    }
}
