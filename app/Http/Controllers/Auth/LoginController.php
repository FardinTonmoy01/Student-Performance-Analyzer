<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AssignClassTeacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('backend.auth.login');
    }

    public function userSignIn (Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6'
        ]);

        $isValid = false;
        if ($this->attempToLogin($request, 1)) {   // type = 1 -> Super Admin
            $isValid = true;
        } else if ($this->attempToLogin($request, 2)) { // Type = 2 -> Institute Admin Login
            $isValid = true;
        } else if ($this->attempToLogin($request, 3)) { // type = 3 -> Teacher Login
            $isValid = true;
        } else if ($this->attempToLogin($request, 4)) { // type = 4 -> Parent Login
            $isValid = true;
        } else if ($this->attempToLogin($request, 5)) { // type = 5 -> Student Login
            $isValid = true;
        } else {
            $isValid = false;
        }

        if ($isValid) {
            $permitted = $this->setCustomSession($request);
            if ($permitted == 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login Successfully'
                ], 200);
            } elseif ($permitted == 2) {
                $this->sessionFlush($request);
                return response()->json([
                    'success' => false,
                    'message' => 'Any Institute is not assigned yet! Please contact with Admin.'
                ], 404);
            } elseif ($permitted == 3) {
                $this->sessionFlush($request);
                return response()->json([
                    'success' => false,
                    'message' => 'A mail has been sent to you. Please verify it first before login.'
                ], 404);
            } elseif ($permitted == 5) {
                $this->sessionFlush($request);
                return response()->json([
                    'success' => false,
                    'message' => 'A mail has been sent to you. Please verify it first before login.'
                ], 404);
            } else {
                $this->sessionFlush($request);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized User! Please contact with Admin.'
                ], 401);
            }
        } else {
            $this->sessionFlush($request);
            return response()->json([
                'success' => false,
                'message' => 'Username or password is not valid.'
            ], 422);
        }
    }

    public function sessionFlush ($request) {
        if(Auth::check()){
            Auth::logout();
            $request->session()->flush();
        }
    }

    
    public function setCustomSession ($request) {
        $userData = auth()->user();
        if ($userData->type == 1) {
            return 0;
        } else if ($userData->type == 2) {
            $institue = DB::table('institute_informations')->where('admin_id', $userData->id)->where('status', 1)->first();
            if ($institue && $userData->email_verified_at == null) {
                $request->session()->put('institute_id', $institue->id);
                return 0;
            } else {
                return 2;
            }
        } else if ($userData->type == 3) {
            $teacher = DB::table('teachers')->where('user_id', $userData->id)->where('status', 1)->first();
            if ($teacher && $userData->email_verified_at == null) {
                $request->session()->put('institute_id', $teacher->institute_id);
                $request->session()->put('teacher_id', $teacher->id);

                $assigns = AssignClassTeacher::where('teacher_id', $teacher->id)->get()->toArray();
                if (count($assigns)) {
                    $classIds = array_column($assigns, 'class_id');
                    $classIds = array_unique($classIds);
        
                    $sectionIds = array_column($assigns, 'section_id');
                    $sectionIds = array_unique($sectionIds);

                    $groupIds = array_column($assigns, 'group_id');
                    $groupIds = array_unique($groupIds);
        
                    $shiftIds = array_column($assigns, 'shift_id');
                    $shiftIds = array_unique($shiftIds);

                    $subjectIds = array_column($assigns, 'subject_id');
                    $subjectIds = array_unique($subjectIds);

                    $request->session()->put('class_ids', $classIds);
                    $request->session()->put('section_ids', $sectionIds);
                    $request->session()->put('group_ids', $groupIds);
                    $request->session()->put('shift_ids', $shiftIds);
                    $request->session()->put('subject_ids', $subjectIds);
                }

                return 0;
            } else {
                return 3;
            }
        } else if ($userData->type == 4) {
            $guardian = DB::table('guardians')->where('user_id', $userData->id)->where('status', 1)->first();
            if ($guardian && $userData->email_verified_at == null) {
                $request->session()->put('institute_id', $guardian->institute_id);
                $request->session()->put('guardian_id', $guardian->id);
                $request->session()->put('guardian_child_id', $guardian->student_id);
                return 0;
            } else {
                return 4;
            }
        } else if ($userData->type == 5) {
            $student = DB::table('students')->where('user_id', $userData->id)->where('status', 1)->first();
            if ($student && $userData->email_verified_at == null) {
                $request->session()->put('institute_id', $student->institute_id);
                $request->session()->put('student_id', $student->id);
                return 0;
            } else {
                return 5;
            }
        }
        else {
            return 1;
        }
    }


    public function attempToLogin ($request, $type) {
        return Auth::attempt([
            'username'     => $request->username,
            'password'  => $request->password,
            'status' => 1,
            'type' => $type
        ]);   
    }


    public function userSignUp (Request $request) {
        $data = $request->validate([
            'name'  =>  'required',
            'email'  => 'required|email|unique:users,email',
            'mobile_no'  => 'required|unique:users,mobile_no',
            'password'  =>  'required|min:6'
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'username' => $request['mobile_no'],
            'mobile_no' => $request['mobile_no'],
            'password' => Hash::make($data['password'])
        ]);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Registration successful.'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong! Please Try Again.'
            ], 500);
        }

    }


    public function logout(Request $request)
    {
        if(Auth::check()){
            Auth::logout();
            $request->session()->flush();

            return redirect()->route('frontend.home');
        }
    }
}
