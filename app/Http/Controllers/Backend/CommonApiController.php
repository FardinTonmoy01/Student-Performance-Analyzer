<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AssignClassTeacher;
use App\Models\EvaluatingIndicator;
use App\Models\InstituteInfo;
use App\Models\MarksSettingDetail;
use App\Models\MasterClass;
use App\Models\MasterGroup;
use App\Models\MasterSection;
use App\Models\MasterShift;
use App\Models\MasterSubject;
use App\Models\Student;
use Illuminate\Http\Request;

class CommonApiController extends Controller
{
    public function instituteList ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        $data = InstituteInfo::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('id', $insId);
        })
        ->select('id as value', 'inst_name as text')
        ->get();

        return response()->json($data, 200);
    }

    public function classList ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        // teacher
        $teacherId = session('teacher_id') ?? null;

        $classIds = null;
        if ($teacherId) {
            $classIds = session('class_ids') ?? null;
        }


        $data = MasterClass::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->when($classIds, function ($q) use ($classIds) {
            $q->whereIn('id', $classIds);
        })
        ->select('id as value', 'class_name as text', 'institute_id')
        ->get();

        return response()->json($data, 200);
    }

    public function groupList ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        // teacher
        $teacherId = session('teacher_id') ?? null;

        $classIds = null;
        $groupIds = null;
        if ($teacherId) {
            $classIds = session('class_ids') ?? null;
            $groupIds = session('group_ids') ?? null;
        }

        $data = MasterGroup::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->when($classIds, function ($q) use ($classIds) {
            $q->whereIn('class_id', $classIds);
        })
        ->when($groupIds, function ($q) use ($groupIds) {
            $q->whereIn('id', $groupIds);
        })
        ->select('id as value', 'group_name as text', 'institute_id', 'class_id')
        ->get();

        return response()->json($data, 200);
    }

    public function sectionList ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        // teacher
        $teacherId = session('teacher_id') ?? null;
        $classIds = null;
        $groupIds = null;
        $sectionIds = null;
        if ($teacherId) {
            $classIds = session('class_ids') ?? null;
            // $groupIds = session('group_ids') ?? null;
            $sectionIds = session('section_ids') ?? null;
        }

        $data = MasterSection::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->when($classIds, function ($q) use ($classIds) {
            $q->whereIn('class_id', $classIds);
        })
        ->when($groupIds, function ($q) use ($groupIds) {
            $q->whereIn('group_id', $groupIds);
        })
        ->when($sectionIds, function ($q) use ($sectionIds) {
            $q->whereIn('id', $sectionIds);
        })

        ->select('id as value', 'section_name as text', 'institute_id', 'class_id', 'group_id')
        ->get();

        return response()->json($data, 200);
    }

    public function shiftList ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        // teacher
        $teacherId = session('teacher_id') ?? null;
        $shiftIds = null;
        if ($teacherId) {
            $shiftIds = session('shift_ids') ?? null;
        }

        $data = MasterShift::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->when($shiftIds, function ($q) use ($shiftIds) {
            $q->whereIn('id', $shiftIds);
        })
        ->select('id as value', 'shift_name as text', 'institute_id')
        ->get();

        return response()->json($data, 200);
    }

    public function classWiseSubject (Request $request)
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

        return response()->json($data, 200);
    }


    public function classWiseStudent (Request $request)
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
            $el['id'] = $item->id;
            $el['value'] = $item->id;
            $el['text'] = $item->user->name .' (Roll-'.$item->roll_no.')';
            $el['roll_no'] = $item->roll_no;
            $el['year'] = $item->year;
            $el['institute_id'] = $item->institute_id;
            $el['class_id'] = $item->class_id;
            $el['group_id'] = $item->group_id;
            $el['section_id'] = $item->section_id;
            $el['shift_id'] = $item->shift_id;

            return $el;
        });

        return response()->json($data, 200);
    }


    public function indicators ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        $data = EvaluatingIndicator::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->select('id', 'indicator_name', 'attribute_id')
        ->orderBy('sorting_order', 'ASC')
        ->get();

        return response()->json($data, 200);
    }


    public function subjectWiseSettingsMarks (Request $request)
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        $groupId = $request->group_id ?? null;

        $data = MarksSettingDetail::where(['indicator_id' => $request->indicator_id, 'subject_id' => $request->subject_id])
        ->whereHas('main', function ($q) use ($request, $groupId, $insId) {
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

        return response()->json($data, 200);
    }

    
}
