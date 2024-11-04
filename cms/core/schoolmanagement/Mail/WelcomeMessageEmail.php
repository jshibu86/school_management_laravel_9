<?php

namespace cms\core\schoolmanagement\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WelcomeMessageEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $details;

    public function __construct($emaildetails)
    {
        $this->details = $emaildetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown(
            "schoolmanagement::mail.WelcomeMessageEmail"
        )->subject("Onboard School - Welcome Email");
    }
}
