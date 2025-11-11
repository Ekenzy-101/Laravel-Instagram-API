<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Verification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $address = getenv("MAIL_FROM_ADDRESS");
        $name = getenv("APP_NAME");
        $subject = "{$this->user->verification_code} is your kenzygram code";
        return $this->view('emails.verify-email')
                    ->from($address, $name)
                    ->subject($subject)
                    ->replyTo($this->user->email, $name);
    }
}
