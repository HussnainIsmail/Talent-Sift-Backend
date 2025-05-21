<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $user;

    public function __construct($user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Password Reset OTP')
                    ->view('emails.forgot-password')  // Create this view for OTP email content
                    ->with([
                        'userName' => $this->user->name,
                        'otp' => $this->otp,
                    ]);
    }
}

