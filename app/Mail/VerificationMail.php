<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class VerificationMail extends Mailable {

    use Queueable,
        SerializesModels;

    private object $user;
    private string $hash;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $hash) {

        $this->user = $user;
        $this->hash = $hash;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('mail.email', ['name' => $this->user->email, 'hash' => $this->hash]);
    }

}
