<?php
namespace App\Services;

use App\Models\InstituteInfo;
use App\Models\MasterClass;
use App\Models\MasterGroup;
use App\Models\MasterSection;
use App\Models\MasterShift;
use App\Models\MasterSubject;
use App\Models\PerformanceAttribute;
use App\Models\Student;
use App\Models\Teacher;

class CommonDataService {
    public static function instituteList ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        return InstituteInfo::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('id', $insId);
        })
        ->select('id', 'inst_name')
        ->get();
    }

    public static function teacherList ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        
        return Teacher::where('status', 1)
        ->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->get()
        ->map(function ($item) {
            $data['id'] = $item->id;
            $data['teacher_name'] = $item->user->name;
            $data['auto_id'] = $item->auto_id;
            $data['institute_id'] = $item->institute_id;
            return $data;
        });
    }

    public static function studentList ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        
        return Student::where('status', 1)
        ->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->get()
        ->map(function ($item) {
            $item->student_name = $item->user->name;
            $item->class_name = $item->class->class_name;
            unset($item->user, $item->class);
            return $item;
        });
    }


    public static function classList ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        return MasterClass::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->select('id', 'class_name', 'institute_id')
        ->get();
    }

    public static function groupList ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        return MasterGroup::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->select('id', 'group_name', 'class_id', 'institute_id')
        ->get();
    }

    public static function shiftList ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        return MasterShift::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->select('id', 'shift_name', 'institute_id')
        ->get();
    }

    public static function sectionList ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        return MasterSection::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->get();
    }

    public static function subjectList ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        return MasterSubject::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->get();
    }
    
    public static function attributeList ()
    {
        $insId = session('institute_id') ? session('institute_id') : null;
        return PerformanceAttribute::where('status', 1)->when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })
        ->select('id', 'attribute_name', 'institute_id')
        ->get();
    }
}
