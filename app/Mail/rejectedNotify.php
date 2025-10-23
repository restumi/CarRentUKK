<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class rejectedNotify extends Mailable
{
    use Queueable, SerializesModels;

    public $verification;

    /**
     * Create a new message instance.
     */
    public function __construct($verification)
    {
        $this->verification = $verification;
    }

    public function build()
    {
        if($this->verification->status === 'rejected'){
            return $this->subject('❌ Account Rejected')
                        ->view('emails.account_rejected');
        }
    }
}
