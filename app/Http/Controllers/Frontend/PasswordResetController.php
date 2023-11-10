<?php

namespace App\Http\Controllers\Frontend;

use App\Actions\MailSendAction;
use App\Models\User;
use App\Models\Classes;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;


class PasswordResetController extends Controller
{

    public function password_reset()
    {
        return view('frontend.password-reset.password_reset');
    }

    public function resetPassword(Request $request){
        $request->validate([
            'email'  =>  'required',
        ]);

        $mail = User::where('email', $request->email)->where('status', 1) ->first();

        if($mail){
            User::where('id', $mail->id)->update([
                'remember_token'=>Str::random(32)
            ]);
            // Mail Send
            (new MailSendAction())->handle($mail, 'mail.password_reset', 'Reset Password');

            Alert::info('Password reset code have been sent to your email');
            return redirect()->back();

        }else{
            Alert::warning('Email was not found');
            return redirect()->back();
        }
    }

    public function resetForm($verified_at){

        return view('frontend.password-reset.reset_form',[
            'verified_at'=>$verified_at,
        ]);
    }

    public function updatePassword(Request $request){
        $data = User::where('remember_token',$request->verified_at)->update([
            'password' => Hash::make($request['new_password']),
            'remember_token'=>null
        ]);

        if($data){
            return response()->json([
                'msg'=>"success"
            ],200);

        }else{
            return response()->json([
                'error'=>"error"
            ],500);
        }
    }


    // Mail verfication
    public function verify($token)
    {
        User::where('email_verified_at', $token)->update([
            'email_verified_at'=> null,
            'status' => 1
        ]);

        Alert::success('Congratulations! Your account verified successfully.');

        return redirect()->back();
    }

}



