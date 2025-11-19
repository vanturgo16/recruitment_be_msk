<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFAMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $code;
    public $expired;

    public function __construct($user, $code, $expired)
    {
        $this->user = $user;
        $this->code = $code;
        $this->expired = $expired;
    }

    public function build()
    {
        return $this->subject('Your Two-Factor Authentication Code')
                    ->view('mail.twofa');
    }
}
