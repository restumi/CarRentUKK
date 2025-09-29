<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class approvedNotify extends Mailable
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
        if($this->verification->status === 'approved'){
            return $this->subject('✅ Account Approved')
                    ->view('emails.account_approved');
        }
    }
}
