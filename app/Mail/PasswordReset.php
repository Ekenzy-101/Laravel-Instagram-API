<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $address = getenv("MAIL_FROM_ADDRESS");
        $name = getenv("APP_NAME");
        $username = $this->data["username"];
        $subject = "{$username}, we've made it easy to get back on Kenzygram";
        return $this->view('emails.reset-password')
                    ->from($address, $name)
                    ->subject($subject)
                    ->replyTo($this->data["email"], $name)
                    ->with($this->data);
    }
}
