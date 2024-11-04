<?php

namespace cms\admission\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RejectionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $gender;
    public $rejection_text;
    public $school;
    public $logoUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     * * @param string $name
     * @param string $gender
     * @param string $rejection_text
     * @param string $school
     * @param string $logoUrl
     * @param array $rejection_data
     */
    public function __construct($name, $gender, $rejection_text,$school,$logoUrl, $rejection_data)
    {
        $this->name = $name;        
        $this->gender = $gender;
        $this->rejection_text = $rejection_text;
        $this->school = $school;
        $this->logoUrl = $logoUrl;
        $this->rejection_data = $rejection_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {        
        return $this->markdown('admission::mail.RejectionEmail')
        ->subject('School Admission - Application')
        ->with([
            'name' => $this->name,            
            'gender' => $this->gender,
            'rejection_text' => $this->rejection_text,
            'school' => $this->school,
            'logoUrl' =>  $this->logoUrl,
            'rejection_data' => $this->rejection_data,
            

        ]);
    }
}
