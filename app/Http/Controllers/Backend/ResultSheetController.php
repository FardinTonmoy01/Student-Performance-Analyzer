<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\EvaluatingIndicator;
use App\Models\MarksEntryDetail;
use App\Models\MasterSubject;
use App\Models\Student;
use App\Services\CommonDataService;
use RealRashid\SweetAlert\Facades\Alert;

class ResultSheetController extends Controller
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


        return view('backend.result-sheet.list', ['data' => $data]);
    }


    // View 
    public function view ($id) {
        $data = Student::where('id', $id)->whereHas('marks')->first();
        if ($data) {
            $groupId = $data->group_id ?? null;

            $indicators = EvaluatingIndicator::where('status', 1)->where('institute_id', $data->institute_id)
            ->orderBy('sorting_order', 'asc')->get();

            $subjects = MasterSubject::where('institute_id', $data->institute_id)->where('class_id', $data->class_id)
            ->when($groupId, function ($q) use ($groupId) {
                $q->where('group_id', $groupId);
            })
            ->get();

            $labels = null;
            $rowSpan = 0;
            foreach($subjects as $key => $subject) {
                $maxIndicator = 0;
                foreach ($indicators as $key1 => $item) {
                    $marks = MarksEntryDetail::whereHas('main', function ($q) use ($data, $groupId, $item) {
                        $q->where('year', $data->year)
                        ->where('institute_id', $data->institute_id)
                        ->where('class_id', $data->class_id)
                        ->when($groupId, function ($p) use ($groupId) {
                            $p->where('group_id', $groupId);
                        })
                        ->where('section_id', $data->section_id)
                        ->where('shift_id', $data->shift_id)
                        ->where('indicator_id', $item->id);
                    })
                    ->where('student_id', $data->id)->where('subject_id', $subject->id)
                    ->get();

                    $item->marks = $marks->toArray();
                    $item->total_obtain = $marks->sum('obtain_marks');
    
                    $maxIndicator += count($marks);
                    if ($maxIndicator > 1) {
                        $rowSpan = 2;
                    }
                }

                $subject->indicators = $indicators->toArray();
                $subject->max_count = $maxIndicator;
            }

            $maxTopCountIndex = collect($subjects)->search(function ($item) use ($subjects) {
                return $item['max_count'] == collect($subjects)->max('max_count');
            });

            $labels = $subjects[$maxTopCountIndex]->indicators;
            // dd($subjects);

            return view('backend.result-sheet.result-card', [ 'data' => $data, 'subjects' => $subjects, 'labels' => $labels, 'rowSpan' => $rowSpan ]);
        }
        Alert::error('Result Not Published Yet!', 'Please Try Again.');
        return redirect()->back();
    }


}
