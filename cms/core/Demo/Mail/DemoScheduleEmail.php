<?php

namespace cms\core\Demo\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DemoScheduleEmail extends Mailable
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
        return $this->markdown("Demo::mail.DemoScheduleEmail")->subject(
            "Demo Schedule Details - Online School Management System "
        );
    }
}
