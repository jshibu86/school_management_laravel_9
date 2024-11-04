<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnpaidEmail extends Mailable  
{
    use Queueable, SerializesModels;

    public $name;
    public $unpaid_amount;
    public $gender;
    public $reminder_text;
    public $school;
    public $logoUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $unpaid_amount, $gender, $reminder_text,$school,$logoUrl)
    {
        $this->name = $name;
        $this->unpaid_amount = $unpaid_amount;
        $this->gender = $gender;
        $this->reminder_text = $reminder_text;
        $this->school = $school;
        $this->logoUrl = $logoUrl;
    }
        
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('fees::admin.unpaidmail')
            ->subject('Fees Reminder')
            ->with([
                'name' => $this->name,
                'unpaid_amount' => $this->unpaid_amount,
                'gender' => $this->gender,
                'reminder_text' => $this->reminder_text,
                'school' => $this->school,
                'logoUrl' =>  $this->logoUrl,
            ]);
    }
}
