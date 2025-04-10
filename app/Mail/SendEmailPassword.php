<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmailPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $type;
    public $name;
    public $email;
    public $password;

    public function __construct($type, $name, $email, $password)
    {
        $this->type = $type;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {
        //SUBJECT NAME
        if($this->type == 'New'){
            $subject = "[Notification New User]";
        } else {
            $subject = "[Notification Reset Password]";
        }

        $email = $this->view('mail.sendEmailPassword')->subject($subject);

        return $email;
    }
}
