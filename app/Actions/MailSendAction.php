<?php
namespace App\Actions;

use Illuminate\Support\Facades\Mail;

class MailSendAction {

    public function handle($data, $template, $subject = 'Account Verification')
    {
        Mail::send($template, ['data' => $data], function($msg) use ($data, $subject) {
            $msg->to($data['email']);
            $msg->subject($subject);
        });
    }
}
