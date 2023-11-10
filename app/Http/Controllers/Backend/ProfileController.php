<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    public function index ()
    {
        return view('backend.profile.profile');
    }

    public function changePassword () {
        return view('backend.profile.change_password');
    }



    public function profileUpdate (Request $request) {

        $user = auth()->user();
        $request->validate([
            'name'  =>  'required',
            'email'  => 'required|email|unique:users,email,'.$user->id,
            'mobile_no'  => 'required|unique:users,mobile_no,'.$user->id,
            'photo' => 'mimes:jpeg,jpg,png|max:1024'
        ]);

        $all_data = $request->all();

        $path = 'profile/';
        $old_file = $user->photo ? $user->photo : null;
        if (request()->file('photo')) {
            $fileUp = FileUploadHelper::global_file_upload(request(), 'photo', $path, $old_file);
            $all_data['photo'] = $fileUp['success'] ? $fileUp['data'] : null;
        } else {
            $all_data['photo'] = $user->photo ? $user->photo : null;
        }


        $user = User::where('id', $user->id)->update([
            'name' => $all_data['name'],
            'email' => $all_data['email'],
            'mobile_no' => $all_data['mobile_no'],
            'address' => $all_data['address'],
            'photo' => $all_data['photo'],
        ]);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Profile Updated successfully.'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong! Please Try Again.'
            ], 500);
        }
    }


    public function updatePassword (Request $request) {
        $request->validate([
            'old_password'  =>  'required',
            'password'  =>  'required|min:6',
            'confirm_password' => 'required|min:6'
        ]);

        if (Auth::attempt([
            'password'=>$request->old_password,
            'email'=>auth()->user()->email
        ])) {
            if ($request->password != $request->confirm_password) {
                Alert::error('Password Not Matched!', 'Please Try Again.');
                return redirect()->back();
            }

            $user = User::where('id',auth()->user()->id)->update([
                'password' => Hash::make($request['password']),
            ]);

            if($user){
                toast('Password changes successfully','success')->padding('10px')->width('270px')->timerProgressBar()->hideCloseButton();
                return redirect()->back();
            }else{
                Alert::warning('Opps',"Something went wrong!");
                return redirect()->back();
            }
        }else{
            Alert::warning('Opps',"Incorrect Old Password");
            return redirect()->back();
        }
    }
}
