<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\InstituteInfo;
use App\Models\MasterClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index()
    {
        // institute
        $insId = session('institute_id') ? session('institute_id') : null;
        $institute = null;
        if (auth()->user()->type != 1) {
            $institute = InstituteInfo::where('id', $insId)->first();
        }

        $data['total_institute'] = User::where('type', 2)->count();

        $data['total_teacher'] = Teacher::when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })->count();

        $data['total_student'] = Student::when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })->count();

        $data['total_parent'] = Guardian::when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })->count();

        $data['total_class'] = MasterClass::when($insId, function ($q) use ($insId) {
            $q->where('institute_id', $insId);
        })->count();

        
        return view('backend.dashboard.dashboard', [
            'institute' => $institute,
            'data' => $data
        ]);
    }
}
